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

	class NewsletterListsImportProcessor extends modObjectProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NewsletterSubscriptions';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('newsletter:default');
		
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
				'filename'	=> $this->objectType.'.csv',
				'directory'	=> $this->modx->getOption('core_path').'cache/import/newsletter/',
				'delimiter'	=> ';'
			));
			
			if (null === $this->getProperty('headers')) {
				$this->setProperty('headers', 0);
			}
			
			if (null === $this->getProperty('reset')) {
				$this->setProperty('reset', 0);
			}
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function process() {
			if (!is_dir($this->getProperty('directory'))) {
				if (!mkdir($this->getProperty('directory'), 0777, true)) {
					return $this->failure($this->modx->getLexion('newsletter.import_dir_failed'));
				}
			}
			
			if (!empty($_FILES['file'])) {
				$filename 		= $_FILES['file']['name'];
				$newFilename 	= substr($filename, 0, strrpos($filename, '.')).'.'.time().'.csv';
				$extension 		= substr($filename, strrpos($filename, '.') + 1, strlen($filename));

				if ('csv' == strtolower($extension)) {
					if (move_uploaded_file($_FILES['file']['tmp_name'], $this->getProperty('directory').$newFilename)) {
						if (false !== ($fopen = fopen($this->getProperty('directory').$newFilename, 'r'))) {
							$reset = $this->getProperty('reset');
							$headers = $this->getProperty('headers');
							
							if (!empty($reset)) {
								$this->modx->removeCollection('NewsletterListsSubscriptions', array(
									'list_id' => $this->getProperty('id')
								));
							}
							
							$current = 0;
							$columns = array('email', 'name', 'active', 'data', 'context', 'token');
							
							while (($row = fgetcsv($fopen, 1000, $this->getProperty('delimiter')))) {
								if (0 == $current && !empty($headers)) {
									$columns = $row;
								} else {
									$data = array(
										'email'		=> '',
										'context' 	=> $this->modx->getOption('default_context'),
										'active'	=> 1,
										'token'		=> md5(time())
									);

									foreach ($columns as $key => $value) {
										if (isset($row[$key])) {
											$data[$value] = trim($row[$key]);
										}
									}

									if (!empty($data['email'])) {
										$criterea = array(
											'context' 	=> $data['context'],
											'email'		=> $data['email']
										);
									
										if (null === ($subscription = $this->modx->getObject($this->classKey, $criterea))) {
											$subscription = $this->modx->newObject($this->classKey);
										}
										
										$subscription->fromArray($data);
										
										if (null !== ($list = $this->modx->newObject('NewsletterListsSubscriptions'))) {
											$list->fromArray(array(
												'list_id' => $this->getProperty('id')
											));
										}
										
										$subscription->addMany($list);
										
										$subscription->save();
									}
								}
								
								$current++;
							}
							
							return $this->success($this->modx->lexicon('failed'));
						}
						
						return $this->failure($this->modx->lexicon('newsletter.import_read_failed'));
					}
					
					return $this->failure($this->modx->lexicon('newsletter.import_upload_failed'));
				}
				
				return $this->failure($this->modx->lexicon('newsletter.import_valid_failed'));
			}
			
			return $this->failure($this->modx->lexicon('newsletter.import_valid_failed'));
		}
	}
	
	return 'NewsletterListsImportProcessor';
	
?>