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
		 * @param Array $properties.
		 * @return Boolean.
		 */
		public function subscribe($properties = array()) {
			if (false !== ($values = $this->modx->getOption('values', $properties, false))) {
				switch($this->modx->getOption('type', $properties, 'subscribe')) {
					case 'confirm':
						if (false !== ($email = $this->modx->getOption($properties['confirmKey'], $values, false))) {
							if ($subscription = $this->modx->getObject('NewsletterSubscriptions', array('confirm' => $email))) {
								$this->modx->setPlaceholder('newsletter_last_id', $subscription->id);
								
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
						if (false !== ($email = $this->modx->getOption('email', $values, false))) {
							if (!$subscription = $this->modx->getObject('NewsletterSubscriptions', array('email' => $email))) {
								$subscription = $this->modx->newObject('NewsletterSubscriptions');
							}

							$confirm = md5(time());

							$subscription->fromArray(array_merge($values, array(
								'active'	=> 0,
								'confirm'	=> $confirm,
							)));

							if ($subscription->save()) {
								$groups = $this->modx->getOption('groups', $values, array());
								$groups = is_string($groups) ? explode(',', $groups) : $groups;

								if (false !== ($defaultGroups = $this->modx->getOption('groups', $properties, false))) {
									if (is_string($defaultGroups)) {
										$defaultGroups = explode(',', $defaultGroups);
									}
				
									$groups = array_merge($groups, $defaultGroups);
								}
								
								foreach ($groups as $group) {
									if (null !== ($group = $this->modx->getObject('NewsletterGroups', array('id' => $group)))) {
										if (!$newGroup = $this->modx->getObject('NewsletterSubscriptionsGroups', array('parent_id' => $subscription->id, 'group_id' => $group->id))) {
											if (null !== ($newGroup = $this->modx->newObject('NewsletterSubscriptionsGroups'))) {
												$newGroup->fromArray(array(
													'parent_id'	=> $subscription->id,
													'group_id'	=> $group->id
												));
											
												$newGroup->save();
											}
										}
									}
								}
		
								$this->modx->setPlaceholders(array(
									'newsletter_last_id'		=> $subscription->id,
									'newsletter_confirm_link'	=> $confirm
								));
					
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
						if (false !== ($email = $this->modx->getOption($properties['confirmKey'], $values, false))) {
							if ($subscription = $this->modx->getObject('NewsletterSubscriptions', array('email' => $email))) {
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
						if (false !== ($email = $this->modx->getOption('email', $values, false))) {
							if ($subscription = $this->modx->getObject('NewsletterSubscriptions', array('email' => $email))) {
								$this->modx->setPlaceholder('newsletter_last_id', $subscription->id);
								
								if ($this->modx->getOption('groups', $values, false) || $this->modx->getOption('groups', $properties, false)) {
									$groups = $this->modx->getOption('groups', $values, array());
									$groups = is_string($groups) ? explode(',', $groups) : $groups;

									if (false !== ($defaultGroups = $this->modx->getOption('groups', $properties, false))) {
										if (is_string($defaultGroups)) {
											$defaultGroups = explode(',', $defaultGroups);
										}
				
										$groups = array_merge($groups, $defaultGroups);
									}
	
									foreach ($groups as $group) {
										$this->modx->removeCollection('NewsletterSubscriptionsGroups', array('parent_id' => $subscription->id, 'group_id' => $group));
									}
				
									if (0 == $this->modx->getCount('NewsletterSubscriptionsGroups', array('parent_id' => $subscription->id))) {
										return $subscription->remove();
									} else {
										return true;
									}
								} else {
									$this->modx->removeCollection('NewsletterSubscriptionsGroups', array('parent_id' => $subscription->id));
									
									return $subscription->remove();
								}
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
		public function getCount($groups = array()) {
			$count = 0;
			
			foreach (is_string($groups) ? explode(',', $groups) : $groups as $key => $group) {
				$critera = array(
					'id'		=> $group,
					'context' 	=> $this->modx->resource->context_key
				);
			
				foreach ($this->modx->getCollection('NewsletterGroups', $critera) as $key => $group) {
					$count += (int) $this->modx->getCount('NewsletterSubscriptionsGroups', array('group_id' => $group->id));
				}
			}
				
			return $count;
		}
	}
	
?>