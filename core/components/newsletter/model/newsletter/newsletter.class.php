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

	class Newsletter {
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $modx;
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $config = array();
		
		/**
		 * @acces public.
		 * @param Object $modx.
		 * @param Array $config.
		*/
		function __construct(modX &$modx, array $config = array()) {
			$this->modx =& $modx;
		
			$corePath 		= $this->modx->getOption('newsletter.core_path', $config, $this->modx->getOption('core_path').'components/newsletter/');
			$assetsUrl 		= $this->modx->getOption('newsletter.assets_url', $config, $this->modx->getOption('assets_url').'components/newsletter/');
			$assetsPath 	= $this->modx->getOption('newsletter.assets_path', $config, $this->modx->getOption('assets_path').'components/newsletter/');
		
			$this->config = array_merge(array(
				'basePath'				=> $corePath,
				'corePath' 				=> $corePath,
				'modelPath' 			=> $corePath.'model/',
				'processorsPath' 		=> $corePath.'processors/',
				'elementsPath' 			=> $corePath.'elements/',
				'chunksPath' 			=> $corePath.'elements/chunks/',
				'snippetsPath' 			=> $corePath.'elements/snippets/',
				'templatesPath' 		=> $corePath.'templates/',
				'assetsPath' 			=> $assetsPath,
				'jsUrl' 				=> $assetsUrl.'js/',
				'cssUrl' 				=> $assetsUrl.'css/',
				'assetsUrl' 			=> $assetsUrl,
				'connectorUrl'			=> $assetsUrl.'connector.php',
				'helpurl'				=> 'newsletter',
				'context'				=> 2 == $this->modx->getCount('modContext') ? 0 : 1
			), $config);	
		
			$this->modx->addPackage('newsletter', $this->config['modelPath']);
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function getHelpUrl() {
			return $this->config['helpurl'];
		}
		
		/**
		 * @acces public.
		 * @param String $name.
		 * @param Array $properties.
		 * @return String.
		 */
		public function getChunk($name, $properties = array()) {
			$chunk = null;
			
			if (!isset($this->chunks[$name])) {
				$chunk = $this->_getTplChunk($name);
			
				if (empty($chunk)) {
					$chunk = $this->modx->getObject('modChunk', array('name' => $name));
					
					if ($chunk == false) {
						return false;
					}
				}
				
				$this->chunks[$name] = $chunk->getContent();
			} else {
				$o = $this->chunks[$name];
				$chunk = $this->modx->newObject('modChunk');
				$chunk->setContent($o);
			}
			
			$chunk->setCacheable(false);
			
			return $chunk->process($properties);
		}
		
		/**
		 * @acces public.
		 * @param String $name.
		 * @param String $postfix.
		 * @return String.
		 */
		private function _getTplChunk($name, $postfix = '.chunk.tpl') {
			$chunk = false;
			
			$f = $this->config['chunksPath'].strtolower($name).$postfix;
			
			if (file_exists($f)) {
				$o = file_get_contents($f);
				$chunk = $this->modx->newObject('modChunk');
				$chunk->set('name', $name);
				$chunk->setContent($o);
			}
			
			return $chunk;
		}
		
		/**
		 * @acces public.
		 * @param Array $properties.
		 * @return Boolean.
		 */
		public function subscribe($properties = array()) {
			if (false !== ($values = $this->modx->getOption('values', $properties, false))) {
				switch($this->modx->getOption('type', $properties, 'subscribe')) {
					case 'confirm':
						if (false !== ($confirm = $this->modx->getOption($properties['confirmKey'], $values, false))) {
							if ($subscription = $this->modx->getObject('NewsletterSubscriptions', array('confirm' => $confirm))) {
								$subscription->fromArray(array(
									'active'	=> 1
								));
							
								if ($subscription->save()) {
									if (false !== ($resource = $this->modx->getOption('resource', $properties, false))) {
										$this->modx->sendRedirect($this->modx->makeUrl($resource, null, null, 'full'));
									}
									
									return true;	
								}
							}
						} else {
							return null;
						}
						
						break;
					case 'subscribe':
						if ($this->modx->getOption('email', $values, false)) {
							if (!$subscription = $this->modx->getObject('NewsletterSubscriptions', array('email' => $values['email']))) {
								$subscription = $this->modx->newObject('NewsletterSubscriptions');
							}

							$confirm 	= md5(time());
							$groups 	= $this->modx->getOption('groups', $values, array());

							$subscription->fromArray(array_merge($values, array(
								'active'	=> 0,
								'confirm'	=> $confirm,
								'groups' 	=> is_array($groups) ? implode(',', $groups) : $groups
							)));
								
							if ($subscription->save()) {
								$this->modx->setPlaceholder('newsletter_confirm_link', $confirm);
					
								return true;	
							}
						}
							
						break;
				}
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param Array $properties.
		 * @return Boolean.
		 */
		public function unsubscribe($properties = array()) {
			if (false !== ($values = $this->modx->getOption('values', $properties, false))) {
				switch($this->modx->getOption('type', $properties, 'unsubscribe')) {
					case 'confirm':
						if (false !== ($confirm = $this->modx->getOption($properties['confirmKey'], $values, false))) {
							if ($subscription = $this->modx->getObject('NewsletterSubscriptions', array('email' => $confirm))) {
								if ($subscription->remove()) {
									if (false !== ($resource = $this->modx->getOption('resource', $properties, false))) {
										$this->modx->sendRedirect($this->modx->makeUrl($resource, null, null, 'full'));
									}
									
									return true;
								}
							}
						} else {
							return null;
						}
						
						break;
					case 'unsubscribe':
						if ($this->modx->getOption('email', $values, false)) {
							if ($subscription = $this->modx->getObject('NewsletterSubscriptions', array('email' => $values['email']))) {
								return $subscription->remove();
							}
						}
						
						break;
				}
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param String $groups.
		 * @return Integer.
		 */
		public function getCount($groups) {
			$count = 0;
			
			if (false !== $groups) {
				$groups = explode(',', $groups);
				
				$critera = array(
					'context' => $this->modx->resource->context_key
				);
				
				foreach ($this->modx->getCollection('NewsletterGroups', $critera) as $key => $value) {
					if (!in_array($value->id, $groups)) {
						unset($groups[array_search($value->id)]);
					}
				}
			} else {
				$groups = array();
			}
			
			foreach ($this->modx->getCollection('NewsletterSubscriptions') as $key => $value) {
				$sCount = 0 == count($groups) ? true : false;

				foreach (explode(',', $value->groups) as $groupKey => $groupValue) {
					if (in_array($groupValue, $groups)) {
						$sCount = true;
					}
				}
				
				if ($sCount) {
					$count++;
				}
			}
				
			return $count;
		}
	}
	
?>