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

	class NewslettersSendProcessor extends modObjectUpdateProcessor {
		/**
		 * @acces public.
		 * @var String.
		 */
		public $classKey = 'NewsletterNewsletters';
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $languageTopics = array('newsletter:default');
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $objectType = 'newsletter.newsletters';
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function initialize() {
			$groups = array_filter(array_map('trim', (array) $this->getProperty('groups')));
			
			$this->setProperty('groups', implode(',', $groups));
			
			$emails = array_filter(array_map('trim', explode(',', $this->getProperty('emails'))));
			
			$this->setProperty('emails', implode(',', $emails));

			if (0 == $this->getProperty('type')) {
				$this->setProperty('send', 0);
			} else {
				$this->setProperty('send', 0 == count($groups) && 0 == count($emails) ? 0 : 2);
			}

			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
	    public function afterSave() {
			if (0 == $this->getProperty('type')) {
				if (null !== ($mail = $this->modx->getService('mail', 'mail.modPHPMailer'))) {
					$emails = array();
					
					foreach (array_filter(array_map('trim', explode(',', $this->getProperty('emails')))) as $value) {
						$emails[$value] = array(
							'name'	=> '',
							'email'	=> $value
						);
					}
					
					$groups = array_filter(array_map('trim', (array) $this->getProperty('groups')));
					
					foreach ($this->modx->getCollection('NewsletterSubscriptions', array('active' => 1)) as $key => $value) {
			    		foreach (explode(',', $value->groups) as $id) {
				    		if (in_array($id, $groups) && !array_key_exists($value->email, $emails)) {
					    		$emails[$value->email] = $value->toArray();
				    		}
			    		}
		    		}
		    		
		    		$ch = curl_init();
        			curl_setopt($ch, CURLOPT_URL, $this->getProperty('resource_url'));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$newsletter = curl_exec($ch);
					curl_close($ch); 
		    		
		    		foreach ($emails as $key => $value) {
			    		$mail->setHTML(true);
			    		
			    		$mail->set(modMail::MAIL_FROM, 		$this->modx->getOption('newsletter_email', null, $this->modx->getOption('emailsender')));
						$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('newsletter_name', null, $this->modx->getOption('site_name')));
						$mail->set(modMail::MAIL_SUBJECT, 	str_replace(array('%subscribe_name%', '%subscribe_email%'), array($value['name'], $value['email']), $this->getProperty('resource_name')));
						$mail->set(modMail::MAIL_BODY, 		str_replace(array('%subscribe_name%', '%subscribe_email%'), array($value['name'], $value['email']), $newsletter));
					
						$mail->address('to', $value['email']);
						
						if (!$mail->send()) {
							$this->modx->log(modX::LOG_LEVEL_ERROR, 'An error occurred while trying to send the email: '.$mail->mailer->ErrorInfo);
						}
				
						$mail->reset();
		    		}
				}
			}
			
			return parent::afterSave();
		}
	}
	
	return 'NewslettersSendProcessor';
	
?>