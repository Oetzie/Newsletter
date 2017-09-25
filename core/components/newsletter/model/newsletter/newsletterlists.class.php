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
	 
	class NewsletterLists extends xPDOSimpleObject {
		/**
		 * @access public.
		 * @return Array.
		 */
		public function getSubscriptions($context) {
			$output = array();
			
			foreach ($this->getMany('NewsletterListsSubscriptions') as $list) {
				$c = array(
					'id' 		=> $list->subscription_id,
					'context' 	=> $context,
					'active'	=> 1
				);
				
				if (null !== ($subscription = $list->getOne('NewsletterSubscriptions', $c))) {
					$output[trim($subscription->email)] = array_merge(array(
						'context'	=> $context,
						'name'		=> trim($subscription->name),
						'email'		=> trim($subscription->email)
					), $subscription->getData());
				}
			}
			
			return $output;
		}
		
		/**
		 * @access public.
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
					$c = array(
						'id'		=> $list->subscription_id,
						'context' 	=> $context
					);

					if (null !== ($subscription = $list->getOne('NewsletterSubscriptions', $c))) {
						$output++;	
					}
				}
			}
			
			return $output;
		}
	}
	
?>