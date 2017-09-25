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

	class NewsletterSubscriptionsGetListProcessor extends modObjectGetListProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NewsletterSubscriptions';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('newsletter:default', 'newsletter:site', 'site:newsletter');
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $defaultSortField = 'email';
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $defaultSortDirection = 'ASC';
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'newsletter.subscriptions';
		
		/**
		 * @access public.
		 * @var Object.
		 */
		public $newsletter;
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->newsletter = $this->modx->getService('newsletter', 'Newsletter', $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path').'components/newsletter/').'model/newsletter/');

			$this->setDefaultProperties(array(
				'dateFormat' => $this->modx->getOption('manager_date_format') .', '. $this->modx->getOption('manager_time_format')
			));
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @param Object $c.
		 * @return Object.
		 */
		public function prepareQueryBeforeCount(xPDOQuery $c) {
			$c->where(array(
				'NewsletterSubscriptions.context' => $this->getProperty('context')
			));
			
			$list = $this->getProperty('list');
			
			if (!empty($list)) {
				$c->innerJoin('NewsletterListsSubscriptions', 'NewsletterListsSubscriptions', array(
					'NewsletterListsSubscriptions.subscription_id = NewsletterSubscriptions.id'
				));
				
				$c->where(array(
					'NewsletterListsSubscriptions.list_id' => $list
				));
			}
			
			$query = $this->getProperty('query');
			
			if (!empty($query)) {
				$c->where(array(
					'NewsletterSubscriptions.name:LIKE' 	=> '%'.$query.'%',
					'OR:NewsletterSubscriptions.email:LIKE' => '%'.$query.'%'
				));
			}

			return $c;
		}
		
		/**
		 * @access public.
		 * @param Object $object.
		 * @return Array.
		 */
		public function prepareRow(xPDOObject $object) {
			$array = array_merge($object->toArray(), array(
				'context_name'		=> '',
				'lists'				=> array(),
				'lists_formatted' 	=> array()
			));
			
			if (null !== ($context = $object->getOne('modContext'))) {
				$array['context_name'] = $context->name;
			}
			
			foreach ($object->getLists() as $list) {
				$array['lists'][] = $list->id;
				
				$translationKey = 'newsletter.list_'.$list->name;
			
				if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
					$array['lists_formatted'][$list->id] = $translation;
				} else {
					$array['lists_formatted'][$list->id] = $list->name;
				}
			}
			
			ksort($array['lists_formatted']);

			$array['lists_formatted'] = implode(',', $array['lists_formatted']);

			if (in_array($array['editedon'], array('-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
				$array['editedon'] = '';
			} else {
				$array['editedon'] = date($this->getProperty('dateFormat'), strtotime($array['editedon']));
			}
			
			return $array;	
		}
	}

	return 'NewsletterSubscriptionsGetListProcessor';
	
?>