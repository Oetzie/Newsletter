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
				'namespace'				=> $this->modx->getOption('namespace', $config, 'newsletter'),
				'helpurl'				=> $this->modx->getOption('namespace', $config, 'newsletter'),
				'lexicons'				=> array('newsletter:default', 'newsletter:site'),
				'base_path'				=> $corePath,
				'core_path' 			=> $corePath,
				'model_path' 			=> $corePath.'model/',
				'processors_path' 		=> $corePath.'processors/',
				'elements_path' 		=> $corePath.'elements/',
				'chunks_path' 			=> $corePath.'elements/chunks/',
				'cronjobs_path' 		=> $corePath.'elements/cronjobs/',
				'plugins_path' 			=> $corePath.'elements/plugins/',
				'snippets_path' 		=> $corePath.'elements/snippets/',
				'templates_path' 		=> $corePath.'templates/',
				'assets_path' 			=> $assetsPath,
				'js_url' 				=> $assetsUrl.'js/',
				'css_url' 				=> $assetsUrl.'css/',
				'assets_url' 			=> $assetsUrl,
				'connector_url'			=> $assetsUrl.'connector.php',
				'template'				=> explode(',', $this->modx->getOption('newsletter.template', null, '')),
				'sender_name'			=> $this->modx->getOption('newsletter.name', null, $this->modx->getOption('site_name')),
				'sender_email'			=> $this->modx->getOption('newsletter.email', null, $this->modx->getOption('emailsender')),
				'token'					=> $this->modx->getOption('newsletter.token', null, md5(time())),
				'context'				=> $this->getContexts()
			), $config);
		
			$this->modx->addPackage('newsletter', $this->config['model_path']);
			
			if (is_array($this->config['lexicons'])) {
				foreach ($this->config['lexicons'] as $lexicon) {
					$this->modx->lexicon->load($lexicon);
				}
			} else {
				$this->modx->lexicon->load($this->config['lexicons']);
			}
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function getHelpUrl() {
			return $this->config['helpurl'];
		}
		
		/**
		 * @acces private.
		 * @return Boolean.
		 */
		private function getContexts() {
			return 1 == $this->modx->getCount('modContext', array(
				'key:!=' => 'mgr'
			));
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function hasPermission() {
			$usergroups = $this->modx->getOption('newsletter.admin_groups', null, 'Administrator');
			
			$isMember = $this->modx->user->isMember(explode(',', $usergroups), false);
			
			if (!$isMember) {
				$version = $this->modx->getVersionData();
				
				if (version_compare($version['full_version'], '2.2.1-pl') == 1) {
					$isMember = (bool) $this->modx->user->get('sudo');
				}
			}
			
			return $isMember;
		}
		
		/**
		 * @acces public.
		 * @param String $tpl.
		 * @param Array $properties.
		 * @param String $type.
		 * @return String.
		 */
		public function getTemplate($template, $properties = array(), $type = 'CHUNK') {
			if (0 === strpos($template, '@')) {
				$type 		= substr($template, 1, strpos($template, ':') - 1);
				$template	= substr($template, strpos($template, ':') + 1, strlen($template));
			}
			
			switch (strtoupper($type)) {
				case 'INLINE':
					$chunk = $this->modx->newObject('modChunk', array(
						'name' => $this->config['namespace'].uniqid()
					));
				
					$chunk->setCacheable(false);
				
					$output = $chunk->process($properties, ltrim($template));
				
					break;
				case 'CHUNK':
					$output = $this->modx->getChunk(ltrim($template), $properties);
				
					break;
			}
			
			return $output;
		}
		
		/**
		 * @acces public.
		 * @param Array $properties.
		 * @return Boolean.
		 */
		public function subscribe($properties = array()) {
			$properties = array_merge(array(
				'type'			=> null,
				'values'		=> array(),
				'customValues'	=> array(),
				'lists'			=> array(),
				'confirm'		=> true,
				'confirmParam'	=> null,
				'success'		=> false
			), $properties);
			
			$values = $properties['values'];

			if ('confirm' == $properties['type']) {
				if (isset($values[$properties['confirmParam']])) {
					$criterea = array(
						'context' 	=> $this->modx->context->key,
						'token' 	=> $values[$properties['confirmParam']]
					);

					if ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea)) {
						$subscription->fromArray(array(
							'active'	=> 1
						));
					
						if ($subscription->save()) {
							if ($properties['success']) {
								$this->modx->sendRedirect($this->modx->makeUrl($properties['success'], null, null, 'full'));
							}
						
							return true;	
						}
					}
				}
				
				return null;
			} else {
				if (isset($values['email'])) {
					$token = md5(time());
					
					$criterea = array(
						'context' 	=> $this->modx->context->key,
						'email' 	=> $values['email']
					);
							
					if (null === ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea))) {
						$subscription = $this->modx->newObject('NewsletterSubscriptions');
					}

					$subscription->fromArray(array_merge($values, array(
						'context'	=> $this->modx->context->key,
						'active'	=> (bool) $properties['confirm'] ? 0 : 1,
						'token'		=> $token
					)));

					if ($subscription->save()) {
						$lists = $properties['lists'];
						
						if (!is_array($lists)) {
							$lists = explode(',', $lists);
						}
						
						if (isset($values['lists'])) {
							if (!is_array($values['lists'])) {
								$lists = $lists + explode(',', $values['lists']);
							} else {
								$lists = $lists + $values['lists'];
							}
						}
						
						$criterea = array(
							'primary' => 1
						);
						
						foreach ($this->modx->getCollection('NewsletterLists', $criterea) as $list) {
							$lists[] = $list->id;
						}

						foreach (array_filter(array_unique($lists)) as $list) {
							$criterea = array(
								'id' => $list
							);
							
							if (null !== ($list = $this->modx->getObject('NewsletterLists', $criterea))) {
								$criterea = array(
									'list_id'			=> $list->id,
									'subscription_id' 	=> $subscription->id
								);

								if (null === $list->getOne('NewsletterListsSubscriptions', $criterea)) {
									$criterea = array(
										'list_id' => $list->id
									);

									if (null !== ($list = $this->modx->newObject('NewsletterListsSubscriptions', $criterea))) {
										$subscription->addMany($list);
									}
								}
							}
						}

						$customValues = $properties['customValues'];
						
						if (is_string($customValues)) {
							$customValues = explode(',', $customValues);
						}
						
						foreach (array_filter($customValues) as $key) {
							if (isset($values[$key])) {
								$value = $values[$key];
								
								if (!is_string($value)) {
									$value = implode(',', $value);
								}
								
								$criterea = array(
									'subscription_id' 	=> $subscription->id,
									'key' 				=> $key
								);

								if (null === ($extra = $this->modx->getObject('NewsletterSubscriptionsExtras', $criterea))) {
									$extra = $this->modx->newObject('NewsletterSubscriptionsExtras');
								}

								$extra->fromArray(array_merge($criterea, array(
									'subscription_id'	=> $subscription->id,
									'key'				=> $key,
									'content' 			=> $value
								)));
									
								$subscription->addMany($extra);

							}
						}
						
						if ($subscription->save()) {
							$this->modx->setPlaceholders(array(
								'newsletter_token'			=> $token,
								'newsletter_last_id'		=> $subscription->id,
								'newsletter_confirm_url'	=> $this->modx->makeUrl($this->modx->getOption('page.newsletter_subscribe'), '', array(
									'token'	=> $token
								), 'full')
							));
							
							if (!(bool) $properties['confirm']) {
								if ($properties['success']) {
									$this->modx->sendRedirect($this->modx->makeUrl($properties['success'], null, null, 'full'));
								}
							}

							return true;
						}
					}
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
			$properties = array_merge(array(
				'type'			=> null,
				'values'		=> array(),
				'lists'			=> array(),
				'confirmParam'	=> null,
				'success'		=> false
			), $properties);
			
			$values = $properties['values'];

			if ('confirm' == $properties['type']) {
				if (isset($values[$properties['confirmParam']])) {
					$criterea = array(
						'context' 	=> $this->modx->context->key,
						'email' 	=> $values[$properties['confirmParam']]
					);

					if (null !== ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea))) {
						$lists = $properties['lists'];
						
						if (!is_array($lists)) {
							$lists = explode(',', $lists);
						}
						
						if (isset($values['lists'])) {
							if (!is_array($values['lists'])) {
								$lists = $lists + explode(',', $values['lists']);
							} else {
								$lists = $lists + $values['lists'];
							}
						}
						
						$criterea = array(
							'primary' => 1
						);
						
						foreach ($this->modx->getCollection('NewsletterLists', $criterea) as $list) {
							$lists[] = $list->id;
						}
						
						$active = 2;
						
						foreach ($subscription->getMany('NewsletterListsSubscriptions') as $list) {
							if (in_array($list->list_id, $lists)) {
								$list->remove();
							} else {
								$active = $subscription->active;
							}
						}
						
						$subscription->fromArray(array(
							'active' => $active	
						));

						if ($subscription->save()) {
							if ($properties['success']) {
								$this->modx->sendRedirect($this->modx->makeUrl($properties['success'], null, null, 'full'));
							}
							
							return true;
						}
					}
				}
				
				return null;
			} else {
				if (isset($values['email'])) {
					$criterea = array(
						'context' 	=> $this->modx->context->key,
						'email' 	=> $values['email']
					);
					
					if (null !== ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea))) {
						$lists = $properties['lists'];
						
						if (!is_array($lists)) {
							$lists = explode(',', $lists);
						}
						
						if (isset($values['lists'])) {
							if (!is_array($values['lists'])) {
								$lists = $lists + explode(',', $values['lists']);
							} else {
								$lists = $lists + $values['lists'];
							}
						}
						
						$criterea = array(
							'primary' => 1
						);
						
						foreach ($this->modx->getCollection('NewsletterLists', $criterea) as $list) {
							$lists[] = $list->id;
						}
						
						$active = 2;
						
						foreach ($subscription->getMany('NewsletterListsSubscriptions') as $list) {
							if (in_array($list->list_id, $lists)) {
								$list->remove();
							} else {
								$active = $subscription->active;
							}
						}
						
						$subscription->fromArray(array(
							'active' => $active	
						));

						if ($subscription->save()) {
							if ($properties['success']) {
								$this->modx->sendRedirect($this->modx->makeUrl($properties['success'], null, null, 'full'));
							}
							
							return true;
						}
					}
				}
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param String $lists.
		 * @return Integer.
		 */
		public function getCount($lists) {
			$count = array();
			
			if (is_string($lists)) {
				$lists = explode(',', $lists);
			}
			
			foreach ($lists as $id) {
				if (null !== ($list = $this->modx->getObject('NewsletterLists', array('id' => $id)))) {
					$count[$id] = array(
						'count' => $list->getSubscriptionsCount($this->modx->resource->context_key)
					);
				}
			}
				
			return $count;
		}
	}
	
?>