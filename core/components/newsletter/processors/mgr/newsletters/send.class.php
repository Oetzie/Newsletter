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
		 * @var Object.
		 */
		public $newsletter;
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function initialize() {
			require_once $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path').'components/newsletter/').'/model/newsletter/newsletter.class.php';
			
			$this->newsletter = new Newsletter($this->modx);

			if ('now' == $this->getProperty('send_at') || empty($this->getProperty('lists'))) {
				$this->setProperty('send', 0);
			} else {
				$this->setProperty('send', 2);
			}
			
			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
	    public function beforeSave() {
		    $this->modx->removeCollection('NewsletterListsNewsletters', array('newsletter_id' => $this->getProperty('id')));
		    
			if (null !== ($lists = $this->getProperty('lists'))) {
				foreach ($lists as $id) {
					if (null !== ($list = $this->modx->newObject('NewsletterListsNewsletters', array('list_id' => $id)))) {
						$this->object->addMany($list);
					}
				}
			}
			
			if ('now' == $this->getProperty('send_at')) {
				if (null !== ($mail = $this->modx->getService('mail', 'mail.modPHPMailer'))) {
					$criterea = array(
						'id' 		=> $this->getProperty('resource_id'),
						'deleted' 	=> 0
					);
			
					if (null !== ($resource = $this->modx->getObject('modResource', $criterea))) {
						if (in_array($resource->template, $this->modx->getOption('template', $this->newsletter->config, array()))) {
							$emails = array();
							
							foreach (explode(',', $this->getProperty('emails')) as $email) {
								if (!empty($email)) {
									$emails[trim($email)] = array(
										'name'	=> '',
										'email'	=> trim($email)	
									);
								}
							}
							
							foreach ($this->object->getMany('NewsletterListsNewsletters') as $newsletterList) {
								$list = $newsletterList->getOne('NewsletterLists');
									
								foreach ($list->getMany('NewsletterListsSubscriptions') as $newsletterSubscription) {
									$criterea = array(
										'id' 		=> $newsletterSubscription->subscription_id,
										'context' 	=> $resource->context_key,
										'active'	=> 1
									);
									
									if (null !== ($subscription = $newsletterSubscription->getOne('NewsletterSubscriptions', $criterea))) {
										$emails[trim($subscription->email)] = array(
											'name'	=> trim($subscription->name),
											'email'	=> trim($subscription->email)
										);
									}
								}
							}
							
							$curl = curl_init();
							
							curl_setopt($curl, CURLOPT_HEADER, false);
							curl_setopt($curl, CURLOPT_URL, $this->modx->makeUrl($resource->id, null, null, 'full'));
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
							
							$newsletterContent = curl_exec($curl);
							
							curl_close($curl);
							
							foreach ($emails as $key => $value) {
								$newsletterTitle = empty($resource->longtitle) ? $resource->pagetitle : $resource->longtitle;
								
					    		$mail->setHTML(true);
					    		
					    		$mail->set(modMail::MAIL_FROM, 		$this->modx->getOption('emailSender', $this->newsletter->config));
								$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('emailName', $this->newsletter->config));
								$mail->set(modMail::MAIL_SUBJECT, 	str_replace(array('{{subscribe_name}}', '{{subscribe_email}}'), array($value['name'], $value['email']), $newsletterTitle));
								$mail->set(modMail::MAIL_BODY, 		str_replace(array('{{subscribe_name}}', '{{subscribe_email}}'), array($value['name'], $value['email']), $newsletterContent));
							
								$mail->address('to', $value['email']);
								
								if (!$mail->send()) {
									$this->modx->log(modX::LOG_LEVEL_ERROR, '[Newsletter] An error occurred while trying to send the email: '.$mail->mailer->ErrorInfo);
								}
						
								$mail->reset();
				    		}
						} else {
							$this->failure($this->modx->lexicon('newsletter.newsletter_send_failed_template_desc'));
						}
					} else {
						$this->failure($this->modx->lexicon('newsletter.newsletter_send_failed_resource_desc'));
					}
				} else {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[Newsletter] An error occurred while trying to send a newsletter ('.$this->getProperty('id').'), modPHPMailer could not be loaded.');
					
					$this->failure($this->modx->lexicon('newsletter.newsletter_send_failed_desc'));
				}
			}
					
			return parent::beforeSave();
		}
	}
	
	return 'NewslettersSendProcessor';
	
?>