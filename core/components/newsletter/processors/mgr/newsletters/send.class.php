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
		public $classKey = 'Newsletters';
		
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
			$this->setProperty('send', 0 == count($this->getProperty('groups')) ? 0 : 2);
			
			if (null === ($groups = $this->getProperty('groups'))) {
				$this->setProperty('groups', '');
			} else {
				$this->setProperty('groups', implode(',', $groups));
			}

			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
	    public function afterSave() {
	    	if (null !== ($timing = $this->getProperty('timing'))) {
	    		if (null !== ($mail = $this->modx->getService('mail', 'mail.modPHPMailer'))) {
		    		$emails = array();
		    		$groups = explode(',', $this->getProperty('groups'));
	
		    		foreach ($this->modx->getCollection('Subscriptions', array('active' => 1, 'context' => $this->getProperty('resource_context'))) as $key => $value) {
		    			$value = $value->toArray();
		    			
			    		foreach (explode(',', $value['groups']) as $id) {
				    		if (in_array($id, $groups) && !array_key_exists($value['email'], $emails)) {
					    		$emails[$value['email']] = $value;
				    		}
			    		}
		    		}
		    		
		    		foreach ($emails as $key => $value) {
			    		$mail->setHTML(true);
			    		
			    		$mail->set(modMail::MAIL_FROM, 		$this->modx->getOption('newsletter_email', null, $this->modx->getOption('emailsender')));
						$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('newsletter_name', null, $this->modx->getOption('site_name')));
						$mail->set(modMail::MAIL_SUBJECT, 	str_replace(array('%subscribe_name%', '%subscribe_email%'), array($value['name'], $value['email']), $this->getProperty('resource_name')));
						$mail->set(modMail::MAIL_BODY, 		str_replace(array('%subscribe_name%', '%subscribe_email%'), array($value['name'], $value['email']), file_get_contents($this->getProperty('resource_url'))));
					
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