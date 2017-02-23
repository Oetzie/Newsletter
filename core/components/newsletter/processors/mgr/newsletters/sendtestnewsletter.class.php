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

	class NewsletterNewsletterSendTestNewsletterProcessor extends modObjectUpdateProcessor {
		/**
		 * @acces public.
		 * @var String.
		 */
		public $classKey = 'NewsletterNewsletters';
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $languageTopics = array('newsletter:default', 'newsletter:lists');
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $objectType = 'newsletter.subscriptions';
		
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
			$this->newsletter = $this->modx->getService('newsletter', 'Newsletter', $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path').'components/newsletter/').'model/newsletter/');
			
			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function beforeSave() {
			if (1 == $this->modx->getOption('site_status')) {
				if (null !== ($mail = $this->modx->getService('mail', 'mail.modPHPMailer'))) {
					$resource = $this->object->getNewsletterResource();
					
					$emails = array();

					foreach ($this->object->getSubscriptions() as $list => $subscriptions) {	
						if ('emails' == $list) {
							$this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('newsletter.newsletter_send_to_emails'));
						} else {
							$this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('newsletter.newsletter_send_to_list', array(
								'name'	=> $this->modx->lexicon($list)
							)));
						}
						
						$count = 0;
						
						foreach ($subscriptions as $subscription) {
							if (!in_array($subscription['email'], $emails)) {
								$placeholdes = array();
										
								foreach ($subscription as $key => $value) {
									$placeholders['subscribe_'.$key] = $value;	
								}

								$placeholders = array_merge(array(
									'newsletter_url'	=> 	$this->modx->makeUrl($resource->id, $resource->context_key, $placeholders, 'full')
								), $placeholders);
								
								$mail->setHTML(true);
								
					    		$mail->set(modMail::MAIL_FROM, 		$this->modx->getOption('sender_email', $this->newsletter->config));
								$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('sender_name', $this->newsletter->config));
								$mail->set(modMail::MAIL_BODY, 		$this->object->getNewsletter($this->modx, 'content', $placeholders));
								$mail->set(modMail::MAIL_SUBJECT, 	$this->object->getNewsletter($this->modx, 'title', $placeholders));
								
								$mail->address('to', $subscription['email']);
								
								if (!$mail->send()) {
									$this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('newsletter.newsletter_send_email_error', array(
										'current'	=> $count + 1,
										'total'		=> count($subscriptions),
										'email'		=> $subscription['email']
									)));
								} else {
									$this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('newsletter.newsletter_send_email_success', array(
										'current'	=> $count + 1,
										'total'		=> count($subscriptions),
										'email'		=> $subscription['email']
									)));
								}
						
								$mail->reset();

								$emails[] = $subscription['email'];	
							} else {
								$this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('newsletter.newsletter_send_email_duplicate', array(
									'current'	=> $count + 1,
									'total'		=> count($subscriptions),
									'email'		=> $subscription['email']
								)));
							}
							
							$count++;
							
							sleep(1);
						}
					}
					
					$this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('newsletter.newsletter_send_feedback', array(
						'pagetitle'	=> $resource->pagetitle,
						'total'		=> count($emails)
					)));
					
					sleep(2);
					
					$this->modx->cacheManager->clearCache(array('registry/'.$this->getProperty('register').$this->getProperty('topic')));
				} else {
					$this->failure($this->modx->lexicon('newsletter.newsletter_send_error_desc'));
				}
			} else {
				$this->failure($this->modx->lexicon('newsletter.newsletter_send_error_site_status_desc'));
			}
			
			return parent::beforeSave();
		}
	}

	return 'NewsletterNewsletterSendTestNewsletterProcessor';

?>