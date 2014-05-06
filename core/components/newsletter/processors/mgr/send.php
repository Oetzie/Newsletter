<?php

	/**
	 * Newsletter
	 *
	 * Copyright 2014 by Oene Tjeerd de Bruin <info@oetzie.nl>
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
	require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
	require_once MODX_CONNECTORS_PATH.'index.php';

	require_once $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path').'components/newsletter/').'model/newsletter/newsletter.class.php';
	
	$modx->newsletter = new Newsletter($modx);
	
	$modx->lexicon->load('newsletter:default');
	
	echo '<pre>';
	
	if (!array_key_exists('hash', $_GET) || $_GET['hash'] != $modx->getOption('newsletter_hash', null, false)) {
		echo 'Newsletter hash is not valid.'.PHP_EOL;
	} else if (null === ($mail = $modx->getService('mail', 'mail.modPHPMailer'))) {
		echo 'Newsletter service modPHPMailer could not be loaded.'.PHP_EOL;
	} else {
		foreach ($modx->getCollection('Newsletters', array('active' => 1, 'send' => 2)) as $newsletterKey => $newsletterValue) {
			$newsletterValue->fromArray(array('send' => 1));
			$newsletterValue->save();
			
			if (false !== ($resource = $modx->newsletter->getResource($newsletterValue->resource_id))) {
				$newsletterValue = array_merge(array('resource' => $resource), $newsletterValue->toArray());
				
				$emails = $modx->newsletter->getEmailFromGroup($newsletterValue['groups'], $newsletterValue['resource']['context_key']);
				
				foreach ($emails as $emailKey => $emailValue) {
					$mail->setHTML(true);
		
		    		$mail->set(modMail::MAIL_FROM, 		$modx->getOption('newsletter_email', null, $modx->getOption('emailsender')));
					$mail->set(modMail::MAIL_FROM_NAME, $modx->getOption('newsletter_name', null, $modx->getOption('site_name')));
					$mail->set(modMail::MAIL_SUBJECT, 	str_replace(array('%subscribe_name%', '%subscribe_email%'), array($emailValue['name'], $emailValue['email']), $newsletterValue['resource']['resource_name']));
					$mail->set(modMail::MAIL_BODY, 		str_replace(array('%subscribe_name%', '%subscribe_email%'), array($emailValue['name'], $emailValue['email']), file_get_contents($newsletterValue['resource']['resource_url'])));
				
					$mail->address('to', $emailValue['email']);
					
					if (!$mail->send()) {
						echo 'An error occurred while trying to send the email: '.$mail->mailer->ErrorInfo.PHP_EOL;
						
						$modx->log(modX::LOG_LEVEL_ERROR, 'An error occurred while trying to send the email: '.$mail->mailer->ErrorInfo);
					}
			
					$mail->reset();
				}
				
				echo 'Newsletter "'.$newsletterValue['resource']['resource_name'].'" send to '.count($emails).' email addresses.'.PHP_EOL;
			}
		}
	}
	
	echo '</pre>';

?>