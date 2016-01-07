<?php
	
	/**
	 * Newsletter
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <info@oetzie.nl>
	 *
	 * This file is part of Newsletter, a real estate property listings component
	 * for MODX Revolution.
	 *
	 * Newsletter is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Newsletter is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Newsletter; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

	require_once dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/config.core.php';
	require_once MODX_CORE_PATH.'model/modx/modx.class.php';
	
	$modx = new modX();
	$modx->initialize('web');
	
	$modx->getService('error', 'error.modError');
	$modx->setLogLevel(modX::LOG_LEVEL_INFO);
	$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

	/*
	 * Put the options in the $options variable.
	 * We use getopt for CLI executions and $_GET for http executions.
	 */
	$options = array();
	
	if (XPDO_CLI_MODE) {
	    $options = getopt('', array('token::', 'module::', 'newsletter::', 'filter::', 'debug'));
	} else {
	    $options = $_GET;
	}
	
	$service = $modx->getService('newsletter', 'Newsletter', $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path').'components/newsletter/').'model/newsletter/');

	if ($service instanceof Newsletter) {
		if (isset($options['token']) && $options['token'] == $modx->getOption('token', $service->config)) {
			$modules = array('newsletter', 'newsletters');
			
			if (!isset($options['module'])) {
				$options['module'] = 'newsletters';
			}
			
			if (isset($options['module']) && in_array($options['module'], $modules)) {
				$newsletters = array();
				
				switch ($options['module']) {
					case 'newsletter':
						if (isset($options['newsletter'])) {
							if (false !== ($newsletter = $service->getNewsletter($options['newsletter']))) {
								$newsletters[$newsletter->id] = $newsletter;		
							}
						}

						break;
					case 'newsletters':
						foreach ($modx->getCollection('NewsletterNewsletters') as $newsletter) {
							if (false !== ($newsletter = $service->getNewsletter($newsletter->id))) {
								$newsletters[$newsletter->id] = $newsletter;		
							}	
						}
						
						break;	
				}
				
				if (null !== ($mail = $modx->getService('mail', 'mail.modPHPMailer'))) {
					if (0 == count($newsletters)) {
						$modx->log(modX::LOG_LEVEL_INFO, 'SUCCESS:: No newsletter ready to be send.');
					} else {
						$modx->log(modX::LOG_LEVEL_INFO, 'SUCCESS:: '.count($newsletters).' newsletter(s) ready to be send.');
						
	 					foreach ($newsletters as $newsletter) {
							if (in_array($newsletter->resource->template, $modx->getOption('template', $service->config, array()))) {
								$subscriptions = $service->getSubscriptions($newsletter);
								
								if (isset($options['filter'])) {
									$subscriptions = $modx->runSnippet($options['filter'], array(
										'subscriptions' => $subscriptions
									));
								}
								
								$newsletterCurl = curl_init();
										
								curl_setopt($newsletterCurl, CURLOPT_HEADER, false);
								curl_setopt($newsletterCurl, CURLOPT_RETURNTRANSFER, true);
								
								$newsletterTitle = $newsletter->resource->pagetitle;
									
								if (!empty($newsletter->resource->longtitle)) {
									$newsletterTitle = $newsletter->resource->longtitle;
								}
								
						    	$newsletterTitleChunk = $modx->newObject('modChunk', array(
						    		'name' => sprintf('newsletter-title-%s', uniqid())
						    	));
						    	$newsletterTitleChunk->setCacheable(false);
									
								foreach ($subscriptions as $subscription) {
									$placeholdes = array();
										
									foreach ($subscription as $key => $value) {
										$placeholders['subscribe_'.$key] = $value;	
									}
	
									$placeholders['newsletter_url'] = $modx->makeUrl($newsletter->resource->id, null, $placeholders, 'full');
										
									curl_setopt($newsletterCurl, CURLOPT_URL, str_replace('&amp;', '&', $modx->makeUrl($newsletter->resource->id, null, $placeholders, 'full')));
	  
							    	$mail->setHTML(true);
							    	$mail->set(modMail::MAIL_FROM, 		$modx->getOption('sender_email', $service->config));
									$mail->set(modMail::MAIL_FROM_NAME, $modx->getOption('sender_name', $service->config));
									$mail->set(modMail::MAIL_BODY, 		curl_exec($newsletterCurl));
									$mail->set(modMail::MAIL_SUBJECT, 	$newsletterTitleChunk->process($placeholders, $newsletterTitle));
										
									$mail->address('to', $subscription['email']);
										
									if (!$mail->send()) {
										$modx->log(modX::LOG_LEVEL_INFO, 'ERROR:: Newsletter "'.$newsletterTitle.'" could not send to to "'.$subscription['email'].'": '.$mail->mailer->ErrorInfo);
									} else {
										$modx->log(modX::LOG_LEVEL_INFO, 'SUCCESS:: Newsletter "'.$newsletterTitle.'" send to "'.$subscription['email'].'".');
									}
								
									$mail->reset();
						    	}
						    		
						    	curl_close($newsletterCurl);
							}
						}
					}
				} else {
					$modx->log(modX::LOG_LEVEL_INFO, 'ERROR:: No mail instance.');
				}
			} else {
				$modx->log(modX::LOG_LEVEL_INFO, 'ERROR:: No valid newsletter module.');
			}
		} else {
			$modx->log(modX::LOG_LEVEL_INFO, 'ERROR:: No valid newsletter token.');
		}
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'ERROR:: No newsletter instance.');
	}
		
?>