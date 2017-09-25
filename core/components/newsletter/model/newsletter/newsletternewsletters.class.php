<?php
	
	/**
	 * Newsletter
	 *
	 * Copyright 2017 by Oene Tjeerd de Bruin <modx@oetzie.nl>
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
		 * @access public.
		 * @return Object.
		 */
		public function getNewsletterResource() {
			$c = array(
				'id'		=> $this->resource_id,
				'deleted'	=> 0
			);
			
			if (null !== ($resource = $this->getOne('modResource', $c))) {
				return $resource;
			}
			
			return null;
		}
		
		/**
		 * @access public.
		 * @return Array.
		 */
		public function setSendStatus() {
			if (null !== ($resource = $this->getNewsletterResource())) {
				if (1 == $this->send_repeat || 0 == $this->send_repeat) {
					return array(
						'send_status'	=> 2,
						'send_repeat'	=> 0,
						'send_date'		=> date('Y-m-d H:i:s')
					);
				}
				
				$names 	= array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
				$days 	= array_filter(explode(',', $this->send_days));
				
				if (0 < count($days)) {
					if (7 == ($current = date('N'))) {
						$next = current($days);
					} else {
						if ($current == end($days)) {
							reset($days);
							
							$next = current($days);
						} else {
							$days = array_slice($days, array_search($current, $days));
							
							$next = next($days);
						}
					}
					
					$next = date('Y-m-d', strtotime('Next '.$names[$next - 1]));
				} else {
					$next = date('Y-m-d', strtotime('+1 days'));
				}
				
				if (-1 == $this->send_repeat) {
					$repeat = -1;
				} else {
					$repeat = (int) $this->send_repeat - 1;
				}
				
				return array(
					'send_status'	=> 1,
					'send_repeat'	=> $repeat,
					'send_date'		=> $next.' '.date('H:i:s', strtotime($this->send_date))
				);
			}
		}
		
		/**
		 * @access public.
		 * @return String|Boolean.
		 */
		public function getSendStatus() {
			if (null !== ($resource = $this->getNewsletterResource())) {
				if (1 == $this->send_status) {
					if (-1 == $this->send_repeat || 0 < $this->send_repeat) {
						$start 	= strtotime(date('Y-m-d H:00:00'));
						$end	= strtotime(date('Y-m-d H:00:00')) + ((60 * 60) - 1);

						if (strtotime($this->send_date) >= $start && strtotime($this->send_date) <= $end) {
							$days = array_filter(explode(',', $this->send_days));
	
							if (in_array(date('N'), $days) || 0 == count($days)) {
								return true;
							}
						}
					}
				}
			}

			return false;
		}
		
		/**
		 * @access public.
		 * @param String $type.
		 * @param Array $placeholders.
		 * @return String.
		 */
		public function getNewsletter($type = 'content', $placeholders = array()) {
			if (false !== ($resource = $this->getNewsletterResource())) {
				$output = '';
				
				switch ($type) {
					case 'content':
						$curl = curl_init();
										
						curl_setopt_array($curl, array(
							CURLOPT_HEADER			=> false,
							CURLOPT_RETURNTRANSFER	=> true,
							CURLOPT_URL				=> html_entity_decode($this->xpdo->makeUrl($resource->id, $resource->context_key, array(
								'newsletter' => str_rot13(serialize($placeholders))
							), 'full'))
						));
						
						$output = curl_exec($curl);
						
						curl_close($curl);
						
						break;
					case 'title':
						if (null !== ($title = $this->xpdo->newObject('modChunk'))) {
							$title->fromArray(array(
							    'name' => sprintf('newsletter-title-%s', uniqid())
							));
							
							$title->setCacheable(false);
							
							$output = $title->process($placeholders, $resource->pagetitle);
						}
						
						break;
				}

				return $output;
			}
			
			return false;
		}
		
		/**
		 * @access public.
		 * @return Array.
		 */
		public function getLists() {
			$output = array();
			
			if (null !== ($resource = $this->getNewsletterResource())) {
				foreach ($this->getMany('NewsletterListsNewsletters') as $list) {
					if (null !== ($list = $list->getOne('NewsletterLists'))) {
						$output[] = $list;
					}
				}
			}
				
			return $output;
		}
		
		/**
		 * @access public.
		 * @return Array.
		 */
		public function getSubscriptions() {
			$output = array();
			
			if (null !== ($resource = $this->getNewsletterResource())) {
				foreach ($this->getLists() as $list) {
					$output[$list->name] = $list->getSubscriptions($resource->context_key);
				}
				
				$emails = array_filter(explode(',', $this->send_emails));
				
				if (0 < count($emails)) {
					$output['emails'] = array();
								
					foreach ($emails as $email) {
						if (!empty($email)) {
							$output['emails'][trim($email)] = array(
								'name'		=> '',
								'email'		=> trim($email),
								'context'	=> $resource->context_key
							);
						}
					}
				}
			}
			
			return $output;
		}
		
		/**
		 * @access public.
		 * @param Array $emails.
		 * @return Boolean.
		 */
		public function setSendDetails($emails = array()) {
			$lists = array();
			
			foreach ($this->getLists() as $list) {
				$lists[] = $list->id;
			}
			
			if (null !== ($detail = $this->xpdo->newObject('NewsletterNewslettersDetails'))) {
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
		 * @access public.
		 * @param Boolean $reverse.
		 * @param Integer $limit.
		 * @return Array.
		 */
		public function getSendDetails($reverse = true, $limit = 10) {
			$output = array();
			
			if (false !== ($resource = $this->getNewsletterResource())) {							
				foreach ($this->getMany('NewsletterNewslettersDetails') as $detail) {
					$output[] = $detail;
				}
			}
			
			return array_slice($reverse ? array_reverse($output) : $output, 0, $limit);
		}
	}
	
?>