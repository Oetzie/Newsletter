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

			if ('immediately' == $this->getProperty('send_at') || empty($this->getProperty('lists'))) {
				$this->setProperty('send_status', 0);
			} else {
				$this->setProperty('send_status', 2);
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
			
			$this->object->save();
			
			if ('immediately' == $this->getProperty('send_at')) {
				if (null !== ($mail = $this->modx->getService('mail', 'mail.modPHPMailer'))) {
					if (false !== ($newsletter = $this->newsletter->getNewsletter($this->getProperty('id'), true))) {
						if (in_array($newsletter->get('resource')->template, $this->modx->getOption('template', $this->newsletter->config, array()))) {
							$subscriptions = $this->newsletter->getSubscriptions($newsletter);
							
							$curl = curl_init();
						
							curl_setopt($curl, CURLOPT_HEADER, false);
							curl_setopt($curl, CURLOPT_URL, $this->modx->makeUrl($newsletter->get('resource')->id, null, null, 'full'));
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
							
							$newsletterBody 	= curl_exec($curl);
							$newsletterTitle 	= '' == $newsletter->get('resource')->longtitle ? $newsletter->get('resource')->pagetitle : $newsletter->get('resource')->longtitle;
							
							curl_close($curl);
							
							foreach ($subscriptions as $subscription) {
					    		$mail->setHTML(true);
					    		
					    		foreach ($subscription as $key => $value) {
						    		$newsletterBody = str_replace('{{'.$key.'}}', $value, $newsletterBody);
						    		$newsletterTitle = str_replace('{{'.$key.'}}', $value, $newsletterTitle);
					    		}
					    		
					    		$mail->set(modMail::MAIL_FROM, 		$this->modx->getOption('emailSender', $this->newsletter->config));
								$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('emailName', $this->newsletter->config));
								$mail->set(modMail::MAIL_BODY, 		$newsletterBody);
								$mail->set(modMail::MAIL_SUBJECT, 	$newsletterTitle);
								
								$mail->address('to', $subscription['subscribe_email']);
								
								if (!$mail->send()) {
									$this->modx->log(modX::LOG_LEVEL_ERROR, '[Newsletter] An error occurred while trying to send newsletter (#'.$newsletter->id.') to '.$subscription['subscribe_email'].' : '.$mail->mailer->ErrorInfo);
								} else {
									$succes[] = $subscription['subscribe_email'];
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
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[Newsletter] An error occurred while trying to send newsletters, modPHPMailer could not be loaded.');
					
					$this->failure($this->modx->lexicon('newsletter.newsletter_send_failed_desc'));
				}
			}
					
			return parent::beforeSave();
		}
	}
	
	return 'NewslettersSendProcessor';
	
?>