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
				'helpurl'				=> 'newsletter'
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
		 * @param Array $values.
		 * @param Integer $redirect.
		 * @param String $groups.
		 * @return Boolean.
		 */
		public function subscribe($values, $redirect = false, $groups = null) {
			if (array_key_exists('email', $values) && !empty($values['email'])) {
				$where = array(
					'email' 	=> $values['email'],
					'context'	=> $this->modx->context->key
				);
				
				if (!$subscription = $this->modx->getObject('Subscriptions', $where)) {
					$subscription = $this->modx->newObject('Subscriptions');
				}
				
				$subscription->fromArray(array_merge($values, array(
					'active'	=> 1,
					'groups' 	=> array_key_exists('groups', $values) ? implode(',', $values['groups']) : $groups,
					'context'	=> $this->modx->resource->context_key
				)));
				
				if ($subscription->save()) {
					if (false !== $redirect) {
						$this->modx->sendRedirect($this->modx->makeUrl($redirect, '', '', 'full'));
					}
					
					return true;
				}
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param Array $values.
		 * @param Integer $redirect.
		 * @return Boolean.
		 */
		public function unsubscribe($values, $redirect = false) {
			if (array_key_exists('email', $values) && !empty($values['email'])) {
				$where = array(
					'email' 	=> $values['email'],
					'context'	=> $this->modx->context->key
				);
				
				if ($subscription = $this->modx->getObject('Subscriptions', $where)) {
					if ($subscription->remove()) {
						if (false !== $redirect) {
							$this->modx->sendRedirect($this->modx->makeUrl($redirect, '', '', 'full'));
						}
					
						return true;
					}
				}
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param Integer $id.
		 * @return Array|Boolean.
		 */
		public function getResource($id) {
			if (null !== ($resource = $this->modx->getObject('modResource', $id))) {
				return array_merge(array(
					'resource_url' 		=> $this->modx->makeUrl($resource->id, '', '', 'full'),
					'resource_name'		=> empty($resource->longtitle) ? $resource->pagetitle : $resource->longtitle
				), $resource->toArray());
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param String|Array $groups.
		 * @param Strubg $context.
		 * @return Array.
		 */
		public function getEmailFromGroup($groups, $context) {
			$emails = array();
			
			if (is_string($groups)) {
				$groups = explode(',', $groups);
			}
			
			foreach ($this->modx->getCollection('Subscriptions', array('active' => 1, 'context' => $context)) as $key => $value) {
				$value = $value->toArray();

				foreach (explode(',', $value['groups']) as $id) {
					if (in_array($id, $groups) && !array_key_exists($value['email'], $emails)) {
						$emails[$value['email']] = $value;
					}
				}
			}
			
			return $emails;
		}
	}
	
?>