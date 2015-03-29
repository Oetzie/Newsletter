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

	class SubscriptionsExportProcessor extends modObjectGetListProcessor {
		/**
		 * @acces public.
		 * @var String.
		 */
		public $downloadProperty = 'download';

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
		 * @var Object.
		 */
		public $xml;

		/**
		 * @acces public.
		 * @return mixed.
		 */
		public function process() {
			if (!extension_loaded('XMLWriter') || !class_exists('XMLWriter')) {
				return $this->failure($this->modx->lexicon('xmlwriter_err_nf'));
			}
			
			$download = $this->getProperty($this->downloadProperty);
			
			if (empty($download)) {
				return $this->createDownload();
			}
			
			return $this->download();
		}
		
		/**
		 * @acces public.
		 * @return mixed.
		 */
		public function createDownload() {
			$this->xml = new XMLWriter();
			$this->xml->openMemory();
			$this->xml->startDocument('1.0', 'UTF-8');
			$this->xml->setIndent(true);
			$this->xml->setIndentString('    ');
			
			$this->xml->startElement('newsletter');
			
			if (1 == $this->getProperty('groups')) {
				$this->xml->startElement('groups');
				
				$criteria = $this->modx->newQuery('NewsletterGroups');
				$criteria->sortby('id', 'asc');
				
				foreach ($this->modx->getCollection('NewsletterGroups', $criteria) as $key => $value) {	
					$this->xml->startElement('group');
					$this->xml->writeElement('id', $value->id);
					$this->xml->writeElement('context', $value->context);
					$this->xml->writeElement('name', $value->name);
					$this->xml->writeElement('description', $value->description);
					$this->xml->writeElement('active', $value->active);
					$this->xml->endElement();
				}
				
				$this->xml->endElement();
			}
			
			if (1 == $this->getProperty('subscriptions')) {
				$this->xml->startElement('subscriptions');
				
				$criteria = $this->modx->newQuery($this->classKey);
				$criteria->sortby($this->defaultSortField, $this->defaultSortDirection);
				
				foreach ($this->modx->getCollection($this->classKey, $criteria) as $key => $value) {
					$this->xml->startElement('subscription');
					$this->xml->writeElement('name', $value->name);
					$this->xml->writeElement('email', $value->email);
					$this->xml->writeElement('active', $value->active);
					$this->xml->startElement('groups');
					
					foreach ($value->getMany('NewsletterSubscriptionsGroups') as $subKey => $subValue) {
						$this->xml->writeElement('group', $subValue->group_id);
					}
					
					$this->xml->endElement();
					$this->xml->endElement();
				}
				
				$this->xml->endElement();
			}
			
			$this->xml->endElement();
			
			$this->xml->endDocument();

			$cacheManager = $this->modx->getCacheManager();
			$cacheManager->writeFile($this->modx->getOption('core_path').'export/newsletter/'.$this->objectType.'.xml', $this->xml->outputMemory());
		
			return $this->success($this->objectType.'.xml');
		}
		
		/**
		 * @acces public.
		 * @return mixed.
		 */
		public function download() {
		 	$file = $this->modx->getOption('core_path').'export/newsletter/'.$this->objectType.'.xml';

		 	if (is_file($file)) {
		 		$fileContents = file_get_contents($file);
		 		
		 		header('Content-Type: application/force-download');
		 		header('Content-Disposition: attachment; filename="'.$this->objectType.'.xml"');
		 		
		 		return $fileContents;
			}
			
			return $this->failure($this->objectType.'.xml');
		}
	}

	return 'SubscriptionsExportProcessor';
	
?>