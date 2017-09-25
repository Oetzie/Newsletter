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

	class NewsletterSubscriptionsExportProcessor extends modObjectGetListProcessor {
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
				'filename'	=> $this->objectType.'.csv',
				'directory'	=> $this->modx->getOption('core_path').'cache/export/newsletter/',
				'delimiter'	=> ';'
			));
			
			if (null === $this->getProperty('download')) {
				$this->setProperty('download', 0);
			}
			
			if (null === $this->getProperty('headers')) {
				$this->setProperty('headers', 0);
			}
			
			return parent::initialize();
		}

		/**
		 * @access public.
		 * @return mixed.
		 */
		public function process() {
			if (!is_dir($this->getProperty('directory'))) {
				if (!mkdir($this->getProperty('directory'), 0777, true)) {
					return $this->failure($this->modx->getLexion('newsletter.export_dir_failed'));
				}
			}
			
			$file = $this->getProperty('download');

			if (empty($file)) {
				return $this->setFile();
			}
			
			return $this->getFile();
		}
		
		/**
		 * @access public.
		 * @return mixed.
		 */
		public function setFile() {
			if (false !== ($fopen = fopen($this->getProperty('directory').$this->getProperty('filename'), 'w'))) {
				$columns = array('email', 'name', 'active', 'data', 'context');
				
				$headers = $this->getProperty('headers');
				
				if (!empty($headers)) {
					$rows = array($columns);
				} else {
					$rows = array();
				}
				
				foreach ($this->modx->getCollection($this->classKey) as $subscription) {
					$rows[$subscription->id] = $subscription->toArray();
				}

				foreach ($rows as $key => $value) {
					if (0 == $key) {
						fputcsv($fopen, $value, $this->getProperty('delimiter'));
					} else {
						$data = array();
						
						foreach ($columns as $column) {
							$data[] = $value[$column];
						}
						
						fputcsv($fopen, $data, $this->getProperty('delimiter'));
					}
				}

				
				fclose($fopen);
			
				return $this->success($this->modx->lexicon('success'));
			}
			
			return $this->failure($this->modx->lexicon('newsletter.export_failed'));
		}
		
		/**
		 * @access public.
		 * @return mixed.
		 */
		public function getFile() {
			$file = $this->getProperty('directory').$this->getProperty('filename');
			
			if (is_file($file)) {
				$fileContents = file_get_contents($file);
				
				header('Content-Type: application/force-download');
				header('Content-Disposition: attachment; filename="'.$this->getProperty('filename').'"');
				
				return $fileContents;
			}
			
			return $this->failure($this->modx->lexicon('newsletter.export_failed'));
		}
	}

	return 'NewsletterSubscriptionsExportProcessor';
	
?>