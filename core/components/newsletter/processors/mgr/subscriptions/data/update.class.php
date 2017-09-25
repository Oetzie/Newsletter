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

	class NewsletterSubscriptionsDataUpdateProcessor extends modProcessor {
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

			if (null !== ($key = $this->getProperty('key'))) {
				$this->setProperty('key', strtolower(str_replace(array(' ', '-'), '_', $key)));	
			}
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function process() {
			if (null !== ($object = $this->modx->getObject($this->classKey, $this->getProperty('id')))) {
				$key = $this->getProperty('key');

				if (!preg_match('/^([a-zA-Z0-9\_]+)$/si', $key)) {
					$this->addFieldError('key', $this->modx->lexicon('newsletter.subscription_data_error_character'));
				} else if (!$object->isData($key)) {
					$this->addFieldError('key', $this->modx->lexicon('newsletter.subscription_data_error_exists'));
				} else {
					$object->setData($key, $this->getProperty('content'));
					
					if ($object->save()) {
						return $this->success('', $object->toArray());
					}
				}
				
				return $this->failure();
			} 
			
			return $this->failure($this->modx->lexicon('newsletter.subscription_data_error'));
		}
	}
	
	return 'NewsletterSubscriptionsDataUpdateProcessor';
	
?>