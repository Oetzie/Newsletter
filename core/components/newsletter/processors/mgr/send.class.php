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

	class NewsletterSendProcessor extends modObjectProcessor {
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
			
			$this->setDefaultProperties(array(
				'newsletter' 		=> false,
				'newsletterSnippet'	=> false
			));
			
			return parent::initialize();
		}
				
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function process() {
			if (null !== ($mail = $this->modx->getService('mail', 'mail.modPHPMailer'))) {
				$newsletters = array();
				
				if (false === ($id = $this->getProperty('newsletter'))) {
					foreach ($this->modx->getCollection('NewsletterNewsletters') as $id) {
						if (false !== ($newsletter = $this->newsletter->getNewsletter($id))) {
							$newsletters[$newsletter->id] = $newsletter;		
						}	
					}
				} else {
					if (false !== ($newsletter = $this->newsletter->getNewsletter($id))) {
						$newsletters[$newsletter->id] = $newsletter;		
					}
				}
				
				foreach ($newsletters as $newsletter) {
					if (in_array($newsletter->get('resource')->template, $this->modx->getOption('template', $this->newsletter->config, array()))) {
						$succes = array();
						$subscriptions = $this->newsletter->getSubscriptions($newsletter);
						
						if (false !== ($snippet = $this->getProperty('newsletterSnippet'))) {
							$subscriptions = $this->modx->runSnippet($snippet, array(
								'subscriptions' => $subscriptions
							));
						}
										
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
	
			    		$newsletters[$newsletter->id] = 'Newsletter \''.$newsletterTitle.' ('.$newsletter->id.')\' is send successful to '.count($succes).' subscription(s)';
					}
				}
			} else {
				if (!$this->getProperty('newsletter')) {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[Newsletter] An error occurred while trying to send newsletters, modPHPMailer could not be loaded.');
				} else {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[Newsletter] An error occurred while trying to send newsletter ('.$this->getProperty('newsletter').'), modPHPMailer could not be loaded.');
				}
			}
		
			if (1 == count($newsletters)) {
				return $this->outputArray(array('message' => array_shift($newsletters)));
			} else {
				return $this->outputArray(array('message' => count($newsletters).' newsletters send: '.implode($newsletters, ', ')));
			}
		}
	
	}

	return 'NewsletterSendProcessor';
	
?>