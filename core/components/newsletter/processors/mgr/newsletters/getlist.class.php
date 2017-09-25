<?php

	/**
	 * Newsletter
	 *
	 * Copyright 2017 by Oene Tjeerd de Bruin <info@oetzie.nl>
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

	class NewslettersNewslettersGetListProcessor extends modObjectGetListProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NewsletterNewsletters';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('newsletter:default', 'newsletter:site', 'site:newsletter');
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $defaultSortField = 'id';
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $defaultSortDirection = 'DESC';
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'newsletter.newsletters';
		
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
				'dateFormat' => $this->modx->getOption('manager_date_format') .', '. $this->modx->getOption('manager_time_format'),
				'hidden'	 => false
			));
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @param Object $c.
		 * @return Object.
		 */
		public function prepareQueryBeforeCount(xPDOQuery $c) {
			$c->innerjoin('modResource', 'modResource', array('modResource.id = NewsletterNewsletters.resource_id'));
			$c->innerjoin('modContext', 'modContext', array('modResource.context_key = modContext.key'));
			$c->select($this->modx->getSelectColumns('NewsletterNewsletters', 'NewsletterNewsletters'));
			
			$c->where(array(
				'modResource.context_key' => $this->getProperty('context')
			));
			
			$query = $this->getProperty('query');
			
			if (!empty($query)) {
				$c->where(array(
					'modResource.id:LIKE'			=> '%'.$query.'%',
					'OR:modResource.pagetitle:LIKE' => '%'.$query.'%',
					'OR:modResource.longtitle:LIKE' => '%'.$query.'%'
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
			if ($resource = $object->getNewsletterResource()) {
				$array = array_merge($object->toArray(), array(
					'name' 			=> $resource->pagetitle.($this->modx->hasPermission('tree_show_resource_ids') ? ' ('.$resource->id.')' : ''),
					'published'		=> $resource->published,
					'url'			=> $this->modx->makeUrl($resource->id, null, array(
						'subscribe.name' 	=> $this->modx->getOption('sender_name', $this->newsletter->config, 'test'),
						'subscribe.email'	=> $this->modx->getOption('sender_email', $this->newsletter->config, 'test@test.com')
					), 'full'),
					'send_days'		=> array_filter(explode(',', $object->send_days)),
					'date'			=> date('Y-m-d', time() + (60 * 60 * 24)),
					'time'			=> '00:00',
					'lists'			=> array(),
					'send_details'	=> array()
				));
				
				foreach ($object->getLists() as $list) {
					$array['lists'][] = $list->id;
				}
				
				foreach ($object->getSendDetails() as $detail) {
					$lists = array();
					
					foreach ($detail->getLists() as $list) {
						$lists[$list->id] = $this->modx->lexicon($list->name);	
					}
					
					$array['send_details'][] = array_merge($detail->toArray(), array(
						'lists'				=> array_keys($lists),
						'lists_formatted'	=> implode(', ', $lists),
						'emails_count'		=> explode(',', $detail->emails),
						'timestamp' 		=> date($this->modx->getOption('manager_date_format', 'Y-m-d').', '.$this->modx->getOption('manager_time_format', 'H:i'), strtotime($detail->timestamp))
					));
				}
				
				if (in_array($array['send_date'], array('-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
					$array['send_date'] = '';
				} else {
					$array['send_date'] = date('Y-m-d H:i:s', strtotime($array['send_date']));
					
					$array['date'] = date('Y-m-d', strtotime($array['send_date']));
					$array['time'] = date('H:i', strtotime($array['send_date']));
					
					$array['date_format'] = date($this->modx->getOption('manager_date_format'), strtotime($array['send_date']));
					$array['time_format'] = date($this->modx->getOption('manager_time_format'), strtotime($array['send_date']));
				}
			
				if (in_array($array['editedon'], array('-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
					$array['editedon'] = '';
				} else {
					$array['editedon'] = date($this->getProperty('dateFormat'), strtotime($array['editedon']));
				}
				
				if ($this->newsletter->hasPermission() || 0 == $array['hidden'] || (bool) $this->getProperty('hidden')) {
					return $array;
				}
			}
		}
	}

	return 'NewslettersNewslettersGetListProcessor';
	
?>