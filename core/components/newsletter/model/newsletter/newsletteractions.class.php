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
	 
	require_once dirname( __FILE__ ).'/newsletter.class.php';

	class NewsletterActions extends Newsletter {
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $properties = array();
		
		/**
		 * @acces public.
		 * @param Array $scriptProperties.
		 * @return Boolean.
		 */
		public function setScriptProperties($scriptProperties = array()) {
			$this->properties = array_merge(array(
				'action'		=> null,
				'placeholder'	=> 'newsletter',
				'prefix'		=> null,
				'lists'			=> ''
			), $scriptProperties);

			return $this->setDefaultProperties();
		}
		
		/**
		 * @acces protected.
		 * @return Boolean.
		 */
		protected function setDefaultProperties() {
			$this->properties['placeholder'] = rtrim($this->properties['placeholder'], '.').'.';
			
            return true;
		}
		
		/**
		 * @acces public.
		 * @return Null|Object.
		 */
		public function getForm() {
			if (isset($this->properties['form'])) {
				return $this->properties['form'];
			}
			
			return null;
		}
		
		/**
		 * @acces public.
		 * @param Array $properties.
		 * @return Boolean.
		 */
		public function setAction($properties) {
			$this->setScriptProperties($properties);
			
			switch ($this->properties['action']) {
				case 'subscribe':
					return $this->getActionSubscribe();
					
					break;
				case 'unsubscribe':
					return $this->getActionUnSubscribe();
					
					break;
			}
			
			return false;
		}

		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function getActionSubscribe() {
			if (null !== ($form = $this->getForm())) {
				switch ($this->properties['event']) {	
					case 'onBeforePost':
						$form->setValues($this->modx->request->getParameters());
						
						$values = array_merge(array(
							'token'		=> '',
							'email'		=> '',
						), $form->getValues());
						
						$criterea = array(
							'token'		=> $values['token'],
							'email'		=> $values['email']
						);
						
						if (null !== ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea))) {
							$subscription->fromArray(array(
								'active' => 1
							));
						
							if ($subscription->save()) {
								$this->modx->setPlaceholders(array(
									'subscription' => $subscription->toArray()
								), $this->properties['placeholder']);
								
								if (isset($this->properties['successTpl'])) {
									$form->setScriptProperties(array(
										'tpl' => $this->properties['successTpl']
									));
								}
								
								if (isset($this->properties['emailTpl'])) {
									$email = array(
										'to'			=> array($subscription->email => $subscription->name),
										'from'			=> array($this->modx->getOption('emailsender') => $this->modx->getOption('site_name')),
										'subject'		=> $this->modx->lexicon('newsletter.email_subscribe_confirmed_title'),
										'tpl'			=> $this->properties['emailTpl']
									);
									
									if (isset($this->properties['emailFrom'])) {
										$email['from'] = $this->properties['emailFrom'];
									}
									
									if (isset($this->properties['emailSubject'])) {
										$email['subject'] = $this->properties['emailSubject'];
									}
									
									if (isset($this->properties['emailTplWrapper'])) {
										$email['tplWrapper'] = $this->properties['emailTplWrapper'];
									}

									if (!$form->extensions->invokeExtension('onSuccessPost', 'email', $email)) {
										return false;
									}
								}
							}
						}

						break;		
					case 'onSuccessPost':
						$values = array_merge(array(
							'name'		=> '',
							'email'		=> '',
							'token'		=> md5(time()),
							'lists'		=> ''
						), $form->getValues());
						
						$criterea = array(
							'context' 	=> $this->modx->context->key,
							'email' 	=> $values['email']
						);
								
						if (null === ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea))) {
							$subscription = $this->modx->newObject('NewsletterSubscriptions');
						}
	
						$subscription->fromArray(array_merge($values, array(
							'context'	=> $this->modx->context->key,
							'active'	=> isset($this->properties['confirm']) ? 0 : 1
						)));
						
						if (is_array($this->properties['lists'])) {
							$lists = $this->properties['lists'];
						} else {
							$lists = explode(',', $this->properties['lists']);
						}

						if (isset($values['lists'])) {
							if (!is_array($values['lists'])) {
								$lists = $lists + explode(',', $values['lists']);
							} else {
								$lists = $lists + $values['lists'];
							}
						}
						
						foreach ($this->modx->getCollection('NewsletterLists', array('primary' => 1)) as $list) {
							$lists[] = $list->id;
						}
						
						foreach (array_filter(array_unique($lists)) as $list) {
							$criterea = array(
								'list_id'			=> $list,
								'subscription_id' 	=> $subscription->id
							);

							if (null === $subscription->getOne('NewsletterListsSubscriptions', $criterea)) {
								if (null !== ($list = $this->modx->newObject('NewsletterListsSubscriptions', array('list_id' => $list)))) {
									$subscription->addMany($list);
								}
							}
						}
						
						if (isset($this->properties['extras'])) {
							foreach ($this->properties['extras'] as $key => $type) {
								if (is_numeric($key)) {
									$key = $type;
								}
								
								if (isset($values[$key])) {
									$criterea = array(
										'subscription_id' 	=> $subscription->id,
										'key' 				=> strtolower(str_replace(array(' ', '-'), '_', $type))
									);
	
									if (null === ($extra = $this->modx->getObject('NewsletterSubscriptionsExtras', $criterea))) {
										$extra = $this->modx->newObject('NewsletterSubscriptionsExtras');
									}
	
									$extra->fromArray(array(
										'subscription_id'	=> $subscription->id,
										'key'				=> strtolower(str_replace(array(' ', '-'), '_', $type)),
										'content' 			=> is_string($values[$key]) ? $values[$key] : implode(',', $values[$key])
									));
										
									$subscription->addMany($extra);
								}
							}
						}
						
						if ($subscription->save()) {
							$this->modx->setPlaceholders(array(
								'subscription'	=> $subscription->toArray(),
								'token'			=> $values['token'],
								'last_id'		=> $subscription->id,
								'subscribe_url'	=> $this->modx->makeUrl($this->modx->getOption('newsletter.page_subscribe'), null, array(
									'token'			=> $values['token'],
									'email'			=> $values['email']
								), 'full')
							), $this->properties['placeholder']);
							
							if (isset($this->properties['confirm'])) {
								if (isset($this->properties['confirm']['successTpl'])) {
									$form->setScriptProperties(array(
										'tplSuccess' => $this->properties['confirm']['successTpl']
									));
								}
								
								if (isset($this->properties['confirm']['emailTpl'])) {
									$email = array(
										'to'			=> array($subscription->email => $subscription->name),
										'from'			=> array($this->modx->getOption('emailsender') => $this->modx->getOption('site_name')),
										'subject'		=> $this->modx->lexicon('newsletter.email_subscribe_confirm_title'),
										'tpl'			=> $this->properties['confirm']['emailTpl']
									);
									
									if (isset($this->properties['confirm']['emailFrom'])) {
										$email['from'] = $this->properties['confirm']['emailFrom'];
									}
									
									if (isset($this->properties['confirm']['emailSubject'])) {
										$email['subject'] = $this->properties['confirm']['emailSubject'];
									}
									
									if (isset($this->properties['confirm']['emailTplWrapper'])) {
										$email['tplWrapper'] = $this->properties['confirm']['emailTplWrapper'];
									}

									return $form->extensions->invokeExtension($this->properties['event'], 'email', $email);
								}
							} else {
								if (isset($this->properties['successTpl'])) {
									$form->setScriptProperties(array(
										'tpl' => $this->properties['successTpl']
									));
								}
								
								if (isset($this->properties['emailTpl'])) {
									$email = array(
										'to'			=> array($subscription->email => $subscription->name),
										'from'			=> array($this->modx->getOption('emailsender') => $this->modx->getOption('site_name')),
										'subject'		=> $this->modx->lexicon('newsletter.email_subscribe_confirmed_title'),
										'tpl'			=> $this->properties['emailTpl']
									);
									
									if (isset($this->properties['emailFrom'])) {
										$email['from'] = $this->properties['emailFrom'];
									}
									
									if (isset($this->properties['emailSubject'])) {
										$email['subject'] = $this->properties['emailSubject'];
									}
									
									if (isset($this->properties['emailTplWrapper'])) {
										$email['tplWrapper'] = $this->properties['emailTplWrapper'];
									}

									if (!$form->extensions->invokeExtension('onSuccessPost', 'email', $email)) {
										return false;
									}
								}
							}
							
							return true;
						}
												
						break;
				}
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @return Boolean.
		 */
		public function getActionUnSubscribe() {
			if (null !== ($form = $this->getForm())) {
				switch ($this->properties['event']) {
					case 'onBeforePost':
						$form->setValues($this->modx->request->getParameters());
						
						$values = array_merge(array(
							'email'		=> '',
							'active'	=> 2,
							'lists'		=> ''
						), $form->getValues());
						
						$criterea = array(
							'context' 	=> $this->modx->context->key,
							'email' 	=> $values['email']
						);
								
						if (null !== ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea))) {
							if (is_array($this->properties['lists'])) {
								$lists = $this->properties['lists'];
							} else {
								$lists = explode(',', $this->properties['lists']);
							}
	
							if (isset($values['lists'])) {
								if (!is_array($values['lists'])) {
									$lists = $lists + explode(',', $values['lists']);
								} else {
									$lists = $lists + $values['lists'];
								}
							}
							
							foreach ($this->modx->getCollection('NewsletterLists', array('primary' => 1)) as $list) {
								$lists[] = $list->id;
							}
							
							foreach ($subscription->getMany('NewsletterListsSubscriptions') as $list) {
								if (in_array($list->list_id, $lists)) {
									$list->remove();
								} else {
									$values['active'] = $subscription->active;
								}
							}
							
							$subscription->fromArray(array(
								'active' => $values['active']
							));
							
							
							if ($subscription->save()) {
								return true;
							}
						}

						break;
					case 'onSuccessPost':
						$values = array_merge(array(
							'email'		=> '',
							'active'	=> 2,
							'lists'		=> ''
						), $form->getValues());
						
						$criterea = array(
							'context' 	=> $this->modx->context->key,
							'email' 	=> $values['email']
						);
								
						if (null !== ($subscription = $this->modx->getObject('NewsletterSubscriptions', $criterea))) {
							if (is_array($this->properties['lists'])) {
								$lists = $this->properties['lists'];
							} else {
								$lists = explode(',', $this->properties['lists']);
							}
	
							if (isset($values['lists'])) {
								if (!is_array($values['lists'])) {
									$lists = $lists + explode(',', $values['lists']);
								} else {
									$lists = $lists + $values['lists'];
								}
							}
							
							foreach ($this->modx->getCollection('NewsletterLists', array('primary' => 1)) as $list) {
								$lists[] = $list->id;
							}
							
							foreach ($subscription->getMany('NewsletterListsSubscriptions') as $list) {
								if (in_array($list->list_id, $lists)) {
									$list->remove();
								} else {
									$values['active'] = $subscription->active;
								}
							}
							
							$subscription->fromArray(array(
								'active' => $values['active']
							));
							
							
							if ($subscription->save()) {
								return true;
							}
						}
						
						break;
				}
			}
			
			return false;
		}
	}
	
?>