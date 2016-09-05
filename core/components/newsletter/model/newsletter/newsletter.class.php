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
				'language'				=> 'newsletter:default',
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
			$context = array();
			
			foreach ($this->modx->getCollection('modContext') as $value) {
				if ('mgr' != $value->key) {
					$context[] = $value->toArray();
				}
			}
			
			return 1 == count($context) ? 0 : 1;
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
								'active'	=> (bool) $this->modx->getOption('confirm', $properties) ? 0 : 1,
								'token'		=> $token
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
							
								if (empty(array_filter($defaultLists))) {
									foreach ($this->modx->getCollection('NewsletterLists', array('primary' => 1)) as $list) {
										$defaultLists[] = $list->id;
									}
								}

								foreach (array_filter(array_merge($defaultLists, $lists)) as $id) {
									if (null !== ($list = $this->modx->getObject('NewsletterLists', array('id' => $id)))) {
										$criterea = array(
											'list_id'			=> $list->id,
											'subscription_id' 	=> $subscription->id
										);
					
										if (null === $list->getOne('NewsletterListsSubscriptions', $criterea)) {
											if (null !== ($list = $this->modx->newObject('NewsletterListsSubscriptions', array('list_id' => $id)))) {
												$subscription->addMany($list);
											}
										}
									}
								}
								
								$info 			= $this->modx->getOption('info', $properties, '');
								$reservedInfo 	= array('id', 'context', 'name', 'email', 'token', 'active', 'editedon', 'nospam', 'submit');
								
								if (!is_array($info)) {
									$info = array_filter(explode(',', $info));
								}

								foreach ($values as $key => $value) {
									if (in_array($key, $info) || (empty($info) && !in_array($key, $reservedInfo))) {
										$criterea = array(
											'subscription_id' 	=> $subscription->id,
											'key' 				=> $key
										);
										
										$this->modx->removeCollection('NewsletterSubscriptionsInfo', $criterea);
										
										$criterea = array_merge($criterea, array(
											'content' 			=> is_array($value) ? implode(',', $value) : $value
										));

										if (null !== ($subscriptionInfo = $this->modx->newObject('NewsletterSubscriptionsInfo', $criterea))) {
											$subscription->addMany($subscriptionInfo);
										}
									}
								}
								
								if ($subscription->save()) {
									$this->modx->setPlaceholders(array(
										'newsletter_token'		=> $token,
										'newsletter_last_id'	=> $subscription->id
									));
									
									if (!(bool) $this->modx->getOption('confirm', $properties)) {
										if (false !== ($resource = $this->modx->getOption('resource', $properties, false))) {
											$this->modx->sendRedirect($this->modx->makeUrl($resource, null, null, 'full'));
										}
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
								
								if (empty(array_filter($defaultLists))) {
									foreach ($this->modx->getCollection('NewsletterLists', array('primary' => 1)) as $list) {
										$defaultLists[] = $list->id;
									}
								}
								
								$lists = array_filter(array_merge($defaultLists, $lists));

								if (empty($lists)) {
									$criterea = array(
										'subscription_id' => $subscription->id
									);
								
									$this->modx->removeCollection('NewsletterListsSubscriptions', $criterea);
									$this->modx->removeCollection('NewsletterSubscriptionsInfo', $criterea);
									
									return $subscription->remove();
								} else {
									foreach ($lists as $id) {
										$this->modx->removeCollection('NewsletterListsSubscriptions', array(
											'list_id' 			=> $id,
											'subscription_id' 	=> $subscription->id
										));
									}
									
									$criterea = array(
										'subscription_id' => $subscription->id
									);
									
									if (0 == $this->modx->getCount('NewsletterListsSubscriptions', $criterea)) {
										$this->modx->removeCollection('NewsletterSubscriptionsInfo', $criterea);
										
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
			$count = array();
			
			if (!is_array($lists)) {
				$lists = explode(',', $lists);
			}
			
			foreach ($lists as $id) {
				$count[$id] = array(
					'count'	=> 0
				);
				
				if (null !== ($list = $this->modx->getObject('NewsletterLists', array('id' => $id)))) {
					foreach ($list->getMany('NewsletterListsSubscriptions') as $subscriptions) {
						$criterea = array(
							'id' 		=> $subscriptions->subscription_id,
							'context' 	=> $this->modx->resource->context_key
						);
						
						if (null !== ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea))) {
							$count[$id]['count'] += 1;
						}
					}
				}
			}
				
			return $count;
		}
		
		/**
		 * @acces public.
		 * @param Array $criterea.
		 * @param Boolean $test.
		 * @return Mixed.
		 */
		public function getNewsletter($criterea = array(), $test = false) {
			if (!is_array($criterea)) {
				$criterea = array(
					'id' => $criterea	
				);
			}
			
			if (!$test) {
				$criterea = array_merge(array(
					'send_status' => 2
				), $criterea);
			}
			
			if (null !== ($newsletter = $this->modx->getObject('NewsletterNewsletters', $criterea))) {
				if (strtotime($newsletter->send_date) <= strtotime(date('d-m-Y')) || $test) {
					$criterea = array(
						'id'		=> $newsletter->resource_id,
						'published'	=> 1,
						'deleted'	=> 0
					);
					
					if (null !== ($resource = $newsletter->getOne('modResource', $criterea))) {
						$resource->fromArray(array(
							'cacheable'	=> 0
						));
						
						if ($resource->save()) {
							$newsletter->set('resource', $resource);
							
							$sendDetails = array();
							
							foreach ($newsletter->getMany('NewsletterNewslettersInfo') as $sendDetail) {
								$sendDetails[] = $sendDetail->toArray();
							}
							
							if (0 == $newsletter->send_repeat || $newsletter->send_repeat > count($sendDetails)) {
								if ($newsletter->send_repeat == count($sendDetails) + 1) {
									$newsletter->fromArray(array(
										'send_status' 	=> 1
									));
								} else {
									if (!$test) {
										$newsletter->fromArray(array(
											'send_date'	=> date('Y-m-d', strtotime(date('Y/m/d', strtotime($newsletter->send_date)).'+'.$newsletter->send_interval.' days'))
										));		
									}
								}
								
								if (!$test) {
									$newsletter->addMany($this->modx->newObject('NewsletterNewslettersInfo', array(
										'newsletter_id'	=> $newsletter->id
									)));
								}
	
								if ($newsletter->save()) {
									return $newsletter;
								}
							}
						}
					}
				}
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param Object $newsletter.
		 * @return Array.
		 */
		public function getSubscriptions($newsletter) {
			$subscriptions = array();
										
			foreach (explode(',', $newsletter->emails) as $email) {
				if (!empty($email)) {
					$subscriptions[trim($email)] = array(
						'name'	=> '',
						'email'	=> trim($email)	
					);
				}
			}
			
			foreach ($newsletter->getMany('NewsletterListsNewsletters') as $newsletterList) {
				$list = $newsletterList->getOne('NewsletterLists');
									
				foreach ($list->getMany('NewsletterListsSubscriptions') as $newsletterSubscription) {
					$criterea = array(
						'id' 		=> $newsletterSubscription->subscription_id,
						'context' 	=> $newsletter->resource->context_key,
						'active'	=> 1
					);
									
					if (null !== ($subscription = $newsletterSubscription->getOne('NewsletterSubscriptions', $criterea))) {
						$email = trim($subscription->email);
						
						$subscriptions[$email] = array(
							'name'	=> trim($subscription->name),
							'email'	=> $email
						);
						
						foreach ($subscription->getMany('NewsletterSubscriptionsInfo') as $info) {
							$subscriptions[$email][$info->key] = $info->content;
						}
					}
				}
			}
			
			return $subscriptions;
		}
	}
	
?>