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

	class NewsletterSubscriptionsImportProcessor extends modObjectProcessor {
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
		public $objectType = 'newsletter.newsletters';
		
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
				'filename'	=> $this->objectType.'.csv',
				'directory'	=> $this->modx->getOption('core_path').'cache/import/newsletter/',
				'delimiter'	=> ';'
			));
			
			if (null === $this->getProperty('headers')) {
				$this->setProperty('headers', 0);
			}
			
			return parent::initialize();
		}
		
		/**
		 * @acces public.
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
							$current = 0;
							$columns = array('email', 'name', 'active', 'context', 'token');
							
							while (($row = fgetcsv($fopen, 1000, $this->getProperty('delimiter')))) {
								$headers = $this->getProperty('headers');
								
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
											$data[$value] = $row[$key];
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
	
	return 'NewsletterSubscriptionsImportProcessor';
	
?>