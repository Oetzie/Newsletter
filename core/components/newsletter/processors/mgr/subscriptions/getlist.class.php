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

	class SubscriptionsGetListProcessor extends modObjectGetListProcessor {
		/**
		 * @acces public.
		 * @var String.
		 */
		public $classKey = 'NewsletterSubscriptions';
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $languageTopics = array('newsletter:default');
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $defaultSortField = 'email';
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $defaultSortDirection = 'ASC';
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $objectType = 'newsletter.subscriptions';
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function initialize() {
			$initialized = parent::initialize();
			
			$this->setDefaultProperties(array(
				'dateFormat' => '%b %d, %Y %I:%M %p',
			));
			
			return $initialized;
		}
		
		/**
		 * @acces public.
		 * @param Object $c.
		 * @return Object.
		 */
		public function prepareQueryBeforeCount(xPDOQuery $c) {
			$context = $this->getProperty('context');
			
			if (!empty($context)) {
				$c->where(array('context' => $context));
			}
			
			$query = $this->getProperty('query');
			
			if (!empty($query)) {
				$c->where(array(
					'name:LIKE' 	=> '%'.$query.'%',
					'OR:email:LIKE' => '%'.$query.'%'
				));
			}
			
			return $c;
		}
		
		/**
		 * @acces public.
		 * @param Object $query.
		 * @return Array.
		 */
		public function prepareRow(xPDOObject $object) {
			$array = $object->toArray();
			
			$groups = explode(',', $array['groups']);
			
			foreach ($this->modx->getCollection('Groups') as $key => $value) {
				$value = $value->toArray();
				
				if (in_array($value['id'], $groups)) {
					$groups[array_search($value['id'], $groups)] = $value['name'];
				}
			}
			
			$array['group_names'] = implode(', ', $groups);

			if (in_array($array['editedon'], array('-001-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
				$array['editedon'] = '';
			} else {
				$array['editedon'] = strftime($this->getProperty('dateFormat', '%b %d, %Y %I:%M %p'), strtotime($array['editedon']));
			}
			
			return $array;	
		}
	}

	return 'SubscriptionsGetListProcessor';
	
?>