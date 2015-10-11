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

	class ListsGetListProcessor extends modObjectGetListProcessor {
		/**
		 * @acces public.
		 * @var String.
		 */
		public $classKey = 'NewsletterLists';
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $languageTopics = array('newsletter:default');
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $defaultSortField = 'id';
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $defaultSortDirection = 'ASC';
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $objectType = 'newsletter.lists';
		
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
			$initialized = parent::initialize();
			
			require_once $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path').'components/newsletter/').'/model/newsletter/newsletter.class.php';
			
			$this->newsletter = new Newsletter($this->modx);
			
			$this->setDefaultProperties(array(
				'dateFormat' => '%b %d, %Y %I:%M %p',
				'hidden'	 => false
			));
			
			return $initialized;
		}
		
		/**
		 * @acces public.
		 * @param Object $c.
		 * @return Object.
		 */
		public function prepareQueryBeforeCount(xPDOQuery $c) {
			$query = $this->getProperty('query');
			
			if (!empty($query)) {
				$c->where(array(
					'name:LIKE' => '%'.$query.'%'
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
			$subscriptions = array();

			foreach ($object->getMany('NewsletterListsSubscriptions') as $list) {
				if (null !== ($subscription = $list->getOne('NewsletterSubscriptions'))) {
					$subscriptions[$subscription->id] = $subscription->name;
				}
			}

			$array = array_merge($object->toArray(), array(
				'subscriptions'	=> count($subscriptions)
			));

			if (in_array($array['editedon'], array('-001-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
				$array['editedon'] = '';
			} else {
				$array['editedon'] = strftime($this->getProperty('dateFormat', '%b %d, %Y %I:%M %p'), strtotime($array['editedon']));
			}
			
			if ($this->newsletter->hasPermission() || 0 == $array['hidden'] || (bool) $this->getProperty('hidden')) {
				return $array;
			}	
		}
	}

	return 'ListsGetListProcessor';
	
?>