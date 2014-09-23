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
	
	$modx->newsletter = new Newsletter($modx);
	
	$modx->lexicon->load('newsletter:default');
	
	echo '<pre>';
	
	if (!array_key_exists('hash', $_GET) || $_GET['hash'] != $modx->getOption('hash', $scriptProperties, false)) {
		echo 'Newsletter hash is not valid.'.PHP_EOL;
	} else if (null === ($mail = $modx->getService('mail', 'mail.modPHPMailer'))) {
		echo 'Newsletter service modPHPMailer could not be loaded.'.PHP_EOL;
	} else {
		foreach ($modx->getCollection('NewsletterNewsletters', array('send' => 2)) as $key => $value) {
			if (null !== ($resource = $modx->getObject('modResource', array('id' => $value->resource_id)))) {
				$url = $this->modx->makeUrl($resource->id, '', '', 'full');
				$title = empty($resource->longtitle) ? $resource->pagetitle : $resource->longtitle;
				
				$emails = array();
				
				foreach (array_filter(array_map('trim', explode(',', $value->emails))) as $emailValue) {
					$emails[$emailValue] = array(
						'name'	=> '',
						'email'	=> $emailValue
					);
				}
				
				$groups = array_filter(array_map('trim', explode(',', $value->groups)));
				
				foreach ($modx->getCollection('NewsletterSubscriptions', array('active' => 1)) as $subscriptionKey => $subscriptionValue) {
		    		foreach (explode(',', $subscriptionValue->groups) as $id) {
			    		if (in_array($id, $groups) && !array_key_exists($subscriptionValue->email, $emails)) {
				    		$emails[$subscriptionValue->email] = $subscriptionValue->toArray();
			    		}
		    		}
	    		}

	    		$ch = curl_init();
        		curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$newsletter = curl_exec($ch);
				curl_close($ch); 
				
				foreach ($emails as $emailKey => $emailValue) {
		    		$mail->setHTML(true);
		    		
		    		$mail->set(modMail::MAIL_FROM, 		$modx->getOption('newsletter_email', null, $modx->getOption('emailsender')));
					$mail->set(modMail::MAIL_FROM_NAME, $modx->getOption('newsletter_name', null, $modx->getOption('site_name')));
					$mail->set(modMail::MAIL_SUBJECT, 	str_replace(array('%subscribe_name%', '%subscribe_email%'), array($emailValue['name'], $emailValue['email']), $title));
					$mail->set(modMail::MAIL_BODY, 		str_replace(array('%subscribe_name%', '%subscribe_email%'), array($emailValue['name'], $emailValue['email']), $newsletter));
				
					$mail->address('to', $emailValue['email']);
					
					if (!$mail->send()) {
						$modx->log(modX::LOG_LEVEL_ERROR, 'An error occurred while trying to send the email: '.$mail->mailer->ErrorInfo);
					} else {
						echo 'Newsletter send to '.$emailValue['email'].'.'.PHP_EOL;
					}
			
					$mail->reset();
	    		}
				
				echo 'Newsletter "'.$title.'" send to '.count($emails).' email addresses.'.PHP_EOL;
				
				$value->fromArray(array('send' => 1));
				$value->save();
			}
		}
	}
	
	echo '</pre>';

?>