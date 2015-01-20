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
	 
	require_once $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path').'components/newsletter/').'model/newsletter/newsletter.class.php';
	
	$newsletter = new Newsletter($modx);
	 
	if (null === ($request = $modx->request)) {
		$request = $_GET;
	} else {
		$request = $request->getParameters();
	}
	
	$output = array();
	
	if ($modx->getOption('hash', $request, false) != $modx->getOption('newsletter_cronjob_hash', null, md5(time()))) {
		$output[] = 'Newsletter hash is not valid.';
	} else if (null === ($mail = $modx->getService('mail', 'mail.modPHPMailer'))) {
		$output[] = 'Newsletter service modPHPMailer could not be loaded.';
	} else {
		$output[] = '('.$modx->getCount('NewsletterNewsletters', array('send' => 2)).') newsletter(s) to send.';
		
		foreach ($modx->getCollection('NewsletterNewsletters', array('send' => 2)) as $key => $value) {
			if (strtotime($value->send_date) <= strtotime(date('d-m-Y'))) {
				if (null === ($resource = $modx->getObject('modResource', array('id' => $value->resource_id)))) {
					$output[] = 'Newsletter resource #'.$value->resource_id.' does not exists.';
				} else {
					$value->fromArray(array('send' => 1));
					
					if ($value->save()) {
						$emails = array();
						
						foreach (array_filter(array_map('trim', explode(',', $value->emails))) as $emailValue) {
							$emails[$emailValue] = array(
								'name'	=> '',
								'email'	=> $emailValue
							);
						}
						
						$groups = array_filter(array_map('trim', explode(',', $value->groups)));
						
						foreach ($groups as $key => $value) {
							foreach ($modx->getCollection('NewsletterSubscriptionsGroups', array('group_id' => $value)) as $group) {
								if (null !== ($subscription = $group->getOne('NewsletterSubscriptions'))) {
									$emails[$subscription->email] = $subscription->toArray();
								}
							}
						}
		
			    		$curl = curl_init();
			    		
		        		curl_setopt($curl, CURLOPT_URL, $modx->makeUrl($resource->id, '', '', 'full'));
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
						
						$content = curl_exec($curl);
						
						curl_close($curl); 
						
						$title = empty($resource->longtitle) ? $resource->pagetitle : $resource->longtitle;
						
						foreach ($emails as $emailKey => $emailValue) {
				    		$mail->setHTML(true);
				    		
				    		$mail->set(modMail::MAIL_FROM, 		$modx->getOption('newsletter_email', null, $modx->getOption('emailsender')));
							$mail->set(modMail::MAIL_FROM_NAME, $modx->getOption('newsletter_name', null, $modx->getOption('site_name')));
							$mail->set(modMail::MAIL_SUBJECT, 	str_replace(array('%subscribe_name%', '%subscribe_email%'), array($emailValue['name'], $emailValue['email']), $title));
							$mail->set(modMail::MAIL_BODY, 		str_replace(array('%subscribe_name%', '%subscribe_email%'), array($emailValue['name'], $emailValue['email']), $content));
						
							$mail->address('to', $emailValue['email']);
							
							if (!$mail->send()) {
								$modx->log(modX::LOG_LEVEL_ERROR, 'An error occurred while trying to send the email: '.$mail->mailer->ErrorInfo);
							} else {
								$output[] = 'Newsletter "'.$title.'" send to '.$emailValue['email'].'.';
							}
					
							$mail->reset();
			    		}
						
						$output[] = 'Newsletter "'.$title.'" send to '.count($emails).' email addresses.';
					}
				}
			}
		}
	}
	
	return '<pre>'.PHP_EOL.implode(PHP_EOL, $output).PHP_EOL.'</pre>';

?>