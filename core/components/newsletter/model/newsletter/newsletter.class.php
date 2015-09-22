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
				'template'				=> explode(',', $this->modx->getOption('newsletter_template', null, '')),
				'primaryLists'			=> explode(',', $this->modx->getOption('newsletter_primary_lists', null, '1')),
				'nameSender'			=> $this->modx->getOption('newsletter_name', null, $this->modx->getOption('site_name')),
				'emailSender'			=> $this->modx->getOption('newsletter_email', null, $this->modx->getOption('emailsender')),
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
					case 'complete':
						if (false !== ($token = $this->modx->getOption($this->modx->getOption('param', $properties), $values, false))) {
							$criterea = array(
								'context' 	=> $this->modx->context->key,
								'token' 	=> $token
							);

							if ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea)) {
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
						}
						
						return null;
						
						break;
					default:
						if (!empty($email = $this->modx->getOption('email', $values, ''))) {
							$token = md5(time());
							
							$criterea = array(
								'context' 	=> $this->modx->context->key,
								'email' 	=> $email
							);
									
							if (null === ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea))) {
								$subscription = $this->modx->newObject('NewsletterSubscriptions');
							}

							$subscription->fromArray(array_merge($values, $criterea, array(
								'active'	=> 0,
								'token'		=> $token,
							)));

							if ($subscription->save()) {
								$lists = $this->modx->getOption('lists', $values, '');
								$defaultLists = $this->modx->getOption('lists', $properties, '');
								
								if (!is_array($lists)) {
									$lists = explode(',', $lists);
								}
								
								if (!is_array($defaultLists)) {
									$defaultLists = explode(',', $defaultLists);
								}

								foreach (array_filter(array_merge($defaultLists, $lists)) as $id) {
									if (null !== ($list = $this->modx->getObject('NewsletterLists', array('id' => $id)))) {
										$criterea = array(
											'list_id'			=> $list->id,
											'subscription_id' 	=> $subscription->id
										);
					
										if (null === $list->getOne('NewsletterListsSubscriptions', $criterea)) {
											if (null !== ($newList = $this->modx->newObject('NewsletterListsSubscriptions'))) {
												$newList->fromArray(array(
													'list_id'			=> $list->id,
													'subscription_id' 	=> $subscription->id
												));
											
												$newList->save();
											}
										}
									}
								}
		
								$this->modx->setPlaceholders(array(
									'newsletter_token'		=> $token,
									'newsletter_last_id'	=> $subscription->id
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
					case 'complete':
						if (false !== ($email = $this->modx->getOption($this->modx->getOption('param', $properties), $values, false))) {
							$criterea = array(
								'context' 	=> $this->modx->context->key,
								'email' 	=> $email
							);

							if ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea)) {
								if ($subscription->remove()) {
									if (false !== ($resource = $this->modx->getOption('resource', $properties, false))) {
										$this->modx->sendRedirect($this->modx->makeUrl($resource, null, null, 'full'));
									}
									
									return true;
								}
							}
						}
						
						return null;
						
						break;
					default:
						if (false !== ($email = $this->modx->getOption('email', $values, false))) {
							$criterea = array(
								'context' 	=> $this->modx->context->key,
								'email' 	=> $email
							);
							
							if ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea)) {
								$lists = $this->modx->getOption('lists', $values, '');
								$defaultLists = $this->modx->getOption('lists', $properties, '');
								
								if (!is_array($lists)) {
									$lists = explode(',', $lists);
								}
								
								if (!is_array($defaultLists)) {
									$defaultLists = explode(',', $defaultLists);
								}
								
								$lists = array_filter(array_merge($defaultLists, $lists));

								if (empty($lists)) {
									$this->modx->removeCollection('NewsletterListsSubscriptions', array('subscription_id' => $subscription->id));
									
									return $subscription->remove();
								} else {
									foreach ($lists as $id) {
										$this->modx->removeCollection('NewsletterListsSubscriptions', array(
											'list_id' 			=> $id,
											'subscription_id' 	=> $subscription->id
										));
									}
									
									if (0 == $this->modx->getCount('NewsletterListsSubscriptions', array('subscription_id' => $subscription->id))) {
										return $subscription->remove();
									}
									
									return true;
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
		 * @param String $lists.
		 * @return Integer.
		 */
		public function getCount($lists = array()) {
			$count = 0;
			
			if (!is_array($lists)) {
				$lists = explode(',', $lists);
			}
			
			foreach ($lists as $id) {
				if (null !== ($list = $this->modx->getObject('NewsletterLists', array('id' => $id)))) {
					foreach ($list->getMany('NewsletterListsSubscriptions') as $newList) {
						$criterea = array(
							'id' 		=> $newList->subscription_id,
							'context' 	=> $this->modx->resource->context_key
						);
						
						if (null !== ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea))) {
							$count += 1;
						}
					}
				}
			}
				
			return $count;
		}
	}
	
?>