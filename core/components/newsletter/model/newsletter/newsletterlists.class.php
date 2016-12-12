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
	 
	class NewsletterLists extends xPDOSimpleObject {
		/**
		 * @acces public.
		 * @return Array.
		 */
		public function getSubscriptions($context) {
			$output = array();
			
			foreach ($this->getMany('NewsletterListsSubscriptions') as $list) {
				$criterea = array(
					'id' 		=> $list->subscription_id,
					'context' 	=> $context,
					'active'	=> 1
				);
				
				if (null !== ($subscription = $list->getOne('NewsletterSubscriptions', $criterea))) {
					$key = trim($subscription->email);
					
					$output[$key] = array(
						'name'	=> trim($subscription->name),
						'email'	=> trim($subscription->email)
					);
					
					foreach ($subscription->getMany('NewsletterSubscriptionsExtras') as $value) {
						$output[$key][$value->key] = $value->content;
					}
				}
			}
			
			return $output;
		}
		
		/**
		 * @acces public.
		 * @param String $context.
		 * @return Integer.
		 */
		public function getSubscriptionsCount($context = null) {
			$output = 0;
			
			foreach ($this->getMany('NewsletterListsSubscriptions') as $list) {
				if (null === $context) {
					if (null !== ($subscription = $list->getOne('NewsletterSubscriptions'))) {
						$output++;	
					}
				} else {
					$criterea = array(
						'id'		=> $list->subscription_id,
						'context' 	=> $context
					);

					if (null !== ($subscription = $list->getOne('NewsletterSubscriptions', $criterea))) {
						$output++;	
					}
				}
			}
			
			return $output;
		}
	}
	
?>