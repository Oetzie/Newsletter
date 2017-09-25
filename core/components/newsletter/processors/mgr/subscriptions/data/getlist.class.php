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

	class NewsletterSubscriptionsDataGetListProcessor extends modProcessor {
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
		 * @acces public.
		 * @var String.
		 */
		public $defaultSortField = 'key';
		
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
		 * @var Object.
		 */
		public $newsletter;
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->newsletter = $this->modx->getService('newsletter', 'Newsletter', $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path').'components/newsletter/').'model/newsletter/');

			$this->setDefaultProperties(array(
				'start' 		=> 0,
	            'limit' 		=> 20,
	            'sort' 			=> $this->defaultSortField,
	            'dir' 			=> $this->defaultSortDirection,
	            'query'	 		=> ''
			));
			
			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function process() {
			if (null !== ($object = $this->modx->getObject($this->classKey, $this->getProperty('id')))) {
				$output = array();
				$data	= $object->getData();
				$query 	= $this->getProperty('query');
				
				if ('ASC' == $this->getProperty('dir')) {
					if ('key' == $this->getProperty('sort')) {
						ksort($data);
					} else {
						asort($data);
					}
				} else {
					if ('key' == $this->getProperty('sort')) {
						krsort($data);
					} else {
						arsort($data);
					}
				}
				
				foreach ($data as $key => $value) {
					if (!empty($query)) {
						if (!preg_match('/'.$query.'/i', $key) && !preg_match('/'.$query.'/i', $value)) {
							continue;
						}
					}

					$content = $this->modx->runSnippet('newsletterDataFilter', array(
						'key' 	=> $key,
						'value' => $value
					));
					
					$value = array(
						'key'				=> $key,
						'key_formatted'		=> $key,
						'content'			=> $value,
						'content_formatted'	=> empty($content) ? $value : $content,
						'description'		=> '',
						'subscription'		=> $this->getProperty('id')
					);
					
					$translationKey = 'newsletter.data_'.$key;
		
					if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
						$value['key_formatted'] = $translation;
					}
					
					$translationKey = 'newsletter.data_'.$key.'_desc';
		
					if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
						$value['description'] = $translation;
					}
					
					$output[] = $value;
				}
				
				return $this->outputArray(array_slice($output, $this->getProperty('start'), $this->getProperty('limit')), count($output));
			}
			
			return $this->failure($this->modx->lexicon('newsletter.subscription_data'));
		}
	}

	return 'NewsletterSubscriptionsDataGetListProcessor';
	
?>