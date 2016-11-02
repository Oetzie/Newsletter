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
	 
	class NewsletterNewsletters extends xPDOSimpleObject {	
		/**
		 * @acces public.
		 * @return String|Boolean.
		 */
		public function getSendStatus() {
			if (false !== ($resource = $this->getNewsletterResource())) {
				if (1 == $this->send_status) {
					if (strtotime($this->send_date) <= strtotime(date('d-m-Y')) && $this->send_date != '0000-00-00 00:00:00') {
						$details = $this->getSendDetails();
						
						if (0 < $this->send_repeat) {
							if ($this->send_repeat <= 0) {
								return 'repeat';
							}
						}
					} else {
						return 'date';
					}
				} else {
					return 'status';
				}
			} else {
				return 'resource';
			}
			
			return true;
		}
		
		/**
		 * @acces public.
		 * @param Object $modx.
		 * @param String $type.
		 * @param Array $placeholders.
		 * @return String.
		 */
		public function getNewsletter($modx, $type = 'content', $placeholders = array()) {
			if (false !== ($resource = $this->getNewsletterResource())) {
				$output = '';
				
				if ('content' == $type) {
					$curl = curl_init();
										
					curl_setopt_array($curl, array(
						CURLOPT_HEADER			=> false,
						CURLOPT_RETURNTRANSFER	=> true,
						CURLOPT_URL				=> str_replace('&amp;', '&', $modx->makeUrl($resource->id, $resource->context_key, $placeholders, 'full'))
					));
					
					$output = curl_exec($curl);
					
					curl_close($curl);
				} else if ('title' == $type) {
					if (null !== ($title = $modx->newObject('modChunk'))) {
						$title->fromArray(array(
						    'name' => sprintf('newsletter-title-%s', uniqid())
						));
						$title->setCacheable(false);
						
						$output = $title->process($placeholders, $resource->pagetitle);
					}    	
				}
				
				return $output;
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @return Array.
		 */
		public function getLists() {
			$output = array();
			
			if (false !== ($resource = $this->getNewsletterResource())) {
				foreach ($this->getMany('NewsletterListsNewsletters') as $list) {
					if (null !== ($list = $list->getOne('NewsletterLists'))) {
						$output[] = $list;
					}
				}
			}
				
			return $output;
		}
		
		/**
		 * @acces public.
		 * @return Array.
		 */
		public function getSubscriptions() {
			$output = array();
			
			if (false !== ($resource = $this->getNewsletterResource())) {
				foreach ($this->getLists() as $list) {
					$output[$list->name] = $list->getSubscriptions($resource->context_key);
				}
				
				if (!empty($this->send_emails)) {	
					$output['emails'] = array();
								
					foreach (explode(',', $this->send_emails) as $email) {
						if (!empty($email)) {
							$output['emails'][trim($email)] = array(
								'name'	=> '',
								'email'	=> trim($email)	
							);
						}
					}
				}
			}
			
			return $output;
		}
		
		/**
		 * @acces public.
		 * @param Boolean $reverse.
		 * @return Array.
		 */
		public function getSendDetails($reverse = false) {
			$output = array();
			
			if (false !== ($resource = $this->getNewsletterResource())) {							
				foreach ($this->getMany('NewsletterNewslettersDetails') as $detail) {
					$output[] = $detail;
				}
			}
			
			return $reverse ? array_reverse($output) : $output;
		}
		
		/**
		 * @acces public.
		 * @param Object $modx.
		 * @param Array $emails.
		 * @return Boolean.
		 */
		public function setSendDetail($modx, $emails = array()) {
			$lists = array();
			
			foreach ($this->getLists() as $list) {
				$lists[] = $list->id;
			}
			
			if (null !== ($detail = $modx->newObject('NewsletterNewslettersDetails'))) {
				$detail->fromArray(array(
					'lists'			=> implode(', ', $lists),
					'emails'		=> implode(', ', $emails),
					'timestamp'		=> date('Y-m-d H:i:s')
				));
				
				$this->addMany($detail);
			}
			
			return true;
		}
		
		/**
		 * @acces public.
		 * @return Object.
		 */
		public function getNewsletterResource() {
			$criterea = array(
				'id'		=> $this->resource_id,
				'deleted'	=> 0
			);
			
			if (null !== ($resource = $this->getOne('modResource', $criterea))) {
				return $resource;
			}
			
			return false;
		}
	}
	
?>