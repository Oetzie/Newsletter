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

	class ListsExportProcessor extends modObjectGetListProcessor {
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
		public $newsletter;
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->newsletter = $this->modx->getService('newsletter', 'Newsletter', $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path').'components/newsletter/').'model/newsletter/');

			return parent::initialize();
		}

		/**
		 * @acces public.
		 * @return mixed.
		 */
		public function process() {
			$this->setDefaultProperties(array(
				'filename'	=> $this->objectType.'.csv',
				'directory'	=> $this->modx->getOption('core_path').'cache/export/newsletter/',
				'delimiter'	=> ';'
			));
			
			if (!is_dir($this->getProperty('directory'))) {
				if (!mkdir($this->getProperty('directory'), 0777, true)) {
					return $this->failure($this->modx->getLexion('newsletter.lists_export_dir_failed'));
				}
			}

			if (empty($this->getProperty('download'))) {
				return $this->setFile();
			}
			
			return $this->getFile();
		}
		
		/**
		 * @acces public.
		 * @return mixed.
		 */
		public function setFile() {
			if (false !== ($fopen = fopen($this->getProperty('directory').$this->getProperty('filename'), 'w'))) {
				$columns = array('email', 'name', 'active','context');
				
				$rows = array($columns);
	
				if (null !== ($object = $this->modx->getObject('NewsletterLists', $this->getProperty('id')))) {
					foreach ($object->getMany('NewsletterListsSubscriptions') as $list) {
						if (null !== ($subscription = $list->getOne('NewsletterSubscriptions'))) {
							$rows[$subscription->id] = $subscription->toArray();
						}
					}
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
			
			return $this->failure($this->modx->lexicon('newsletter.lists_export_failed'));
		}
		
		/**
		 * @acces public.
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
			
			return $this->failure($this->modx->lexicon('newsletter.lists_export_failed'));
		}
	}

	return 'ListsExportProcessor';
	
?>