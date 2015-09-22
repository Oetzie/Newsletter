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
			
			return parent::initialize();
		}
				
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function process() {
			$newsletters = array();
			
			if (null !== ($mail = $this->modx->getService('mail', 'mail.modPHPMailer'))) {
				foreach ($this->modx->getCollection('NewsletterNewsletters', array('send' => 2)) as $newsletter) {
					if (strtotime($newsletter->send_date) <= strtotime(date('d-m-Y'))) {
						if (null !== ($resource = $this->modx->getObject('modResource', array('id' => $newsletter->resource_id)))) {
							$newsletters[$newsletter->id] = array();
							
							$newsletter->fromArray(array(
								'send' => 1
							));
							
							if ($newsletter->save()) {
								$emails = array();
									
								foreach (explode(',', $newsletter->emails) as $email) {
									if (!empty($email)) {
										$emails[trim($email)] = array(
											'name'	=> '',
											'email'	=> trim($email)	
										);
									}
								}
									
								foreach ($newsletter->getMany('NewsletterListsNewsletters') as $newsletterList) {
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
								
								foreach ($emails as $subscription) {
									$newsletterTitle = empty($resource->longtitle) ? $resource->pagetitle : $resource->longtitle;
									
						    		$mail->setHTML(true);
						    		
						    		$mail->set(modMail::MAIL_FROM, 		$this->modx->getOption('emailSender', $this->newsletter->config));
									$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('emailName', $this->newsletter->config));
									$mail->set(modMail::MAIL_SUBJECT, 	str_replace(array('{{subscribe_name}}', '{{subscribe_email}}'), array($subscription['name'], $subscription['email']), $newsletterTitle));
									$mail->set(modMail::MAIL_BODY, 		str_replace(array('{{subscribe_name}}', '{{subscribe_email}}'), array($subscription['name'], $subscription['email']), $newsletterContent));
								
									$mail->address('to', $subscription['email']);
									
									if (!$mail->send()) {
										$this->modx->log(modX::LOG_LEVEL_ERROR, '[Newsletter] An error occurred while trying to send newsletter (#'.$newsletter->id.') to '.$subscription['email'].' : '.$mail->mailer->ErrorInfo);
									} else {
										$newsletters[$newsletter->id][] = $subscription['email'];
									}
							
									$mail->reset();
					    		}
							}
						} else {
							$this->modx->log(modX::LOG_LEVEL_ERROR, '[Newsletter] An error occurred while trying to send newsletter (#'.$newsletter->id.'), the resource '.$newsletter->resource_id.' does not exists.');
						}
					}
				}
			} else {
				$this->modx->log(modX::LOG_LEVEL_ERROR, '[Newsletter] An error occurred while trying to send newsletter (#'.$this->getProperty('id').'), modPHPMailer could not be loaded.');
			}
			
			$output = array();
			
			foreach ($newsletters as $id => $subscription) {
				$output[] = 'Newsletter ('.$id.') -> '.count($subscription);
			}
		
			echo json_encode($this->success(count($newsletters).' newsletters send (#'.implode($output, ',').')'));
			
			return parent::process();
		}
	
	}

	return 'NewsletterSendProcessor';
	
?>