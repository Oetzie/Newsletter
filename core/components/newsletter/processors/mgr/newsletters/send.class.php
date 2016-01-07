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
			$this->newsletter = $this->modx->getService('newsletter', 'Newsletter', $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path').'components/newsletter/').'model/newsletter/');

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
		    $this->modx->removeCollection('NewsletterListsNewsletters', array(
		    	'newsletter_id' => $this->getProperty('id')
		    ));
		    
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
						if (in_array($newsletter->resource->template, $this->modx->getOption('template', $this->newsletter->config, array()))) {
							$subscriptions = $this->newsletter->getSubscriptions($newsletter);
							
							$newsletterCurl = curl_init();
									
							curl_setopt($newsletterCurl, CURLOPT_HEADER, false);
							curl_setopt($newsletterCurl, CURLOPT_RETURNTRANSFER, true);
							
							$newsletterTitle = $newsletter->resource->pagetitle;
								
							if (!empty($newsletter->resource->longtitle)) {
								$newsletterTitle = $newsletter->resource->longtitle;
							}
							
					    	$newsletterTitleChunk = $this->modx->newObject('modChunk', array(
					    		'name' => sprintf('newsletter-title-%s', uniqid())
					    	));
					    	$newsletterTitleChunk->setCacheable(false);
							
							foreach ($subscriptions as $subscription) {
								$placeholdes = array();
									
								foreach ($subscription as $key => $value) {
									$placeholders['subscribe_'.$key] = $value;	
								}

								$placeholders['newsletter_url'] = $this->modx->makeUrl($newsletter->resource->id, null, $placeholders, 'full');
									
								curl_setopt($newsletterCurl, CURLOPT_URL, str_replace('&amp;', '&', $this->modx->makeUrl($newsletter->resource->id, null, $placeholders, 'full')));
  
					    		$mail->setHTML(true);
					    		$mail->set(modMail::MAIL_FROM, 		$this->modx->getOption('sender_email', $this->newsletter->config));
								$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('sender_name', $this->newsletter->config));
								$mail->set(modMail::MAIL_BODY, 		curl_exec($newsletterCurl));
								$mail->set(modMail::MAIL_SUBJECT, 	$newsletterTitleChunk->process($placeholders, $newsletterTitle));
								
								$mail->address('to', $subscription['email']);
								
								if (!$mail->send()) {
									$this->modx->log(modX::LOG_LEVEL_ERROR, '[Newsletter] An error occurred while trying to send newsletter (#'.$newsletter->id.') to '.$subscription['email'].' : '.$mail->mailer->ErrorInfo);
								} else {
									$succes[] = $subscription['email'];
								}
						
								$mail->reset();
				    		}
				    		
				    		curl_close($newsletterCurl);
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