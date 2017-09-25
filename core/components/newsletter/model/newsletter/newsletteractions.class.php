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
	 
	require_once dirname( __FILE__ ).'/newsletter.class.php';

	class NewsletterActions extends Newsletter {
		/**
		 * @access public.
		 * @var Array.
		 */
		public $properties = array();
		
		/**
		 * @access public.
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
		 * @access protected.
		 * @return Boolean.
		 */
		protected function setDefaultProperties() {
			$this->properties['placeholder'] = rtrim($this->properties['placeholder'], '.').'.';
			
			if (!is_array($this->properties['lists'])) {
				$this->properties['lists'] = explode(',', $this->properties['lists']);
			}
			
            return true;
		}
		
		/**
		 * @access public.
		 * @return Null|Object.
		 */
		public function getForm() {
			if (isset($this->properties['form'])) {
				return $this->properties['form'];
			}
			
			return null;
		}
		
		/**
		 * @access public.
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
		 * @access public.
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
						
						$c = array(
							'token'		=> $values['token'],
							'email'		=> $values['email']
						);
						
						if (null !== ($subscription = $this->modx->getObject('NewsletterSubscriptions', $c))) {
							$subscription->fromArray(array(
								'active' => 1
							));
						
							if ($subscription->save()) {
								$this->modx->setPlaceholders(array(
									'subscription' => $subscription->toArray()
								), rtrim($this->properties['placeholder'], '.'));
								
								if (isset($this->properties['successTpl'])) {
									$form->setScriptProperties(array(
										'tpl' => $this->properties['successTpl']
									));
								}
								
								if (isset($this->properties['email'])) {
									$email = array_merge(array(
										'to'		=> array($subscription->email => $subscription->name),
										'from'		=> array($this->config['sender_email'] => $this->config['sender_name']),
										'subject'	=> $this->modx->lexicon('newsletter.email_subscribe_confirmed_title')
									), $this->properties['email']);

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
							'context'	=> $this->modx->context->key,
							'token'		=> md5(time()),
							'lists'		=> ''
						), $form->getValues());
						
						$c = array(
							'context' 	=> $values['context'],
							'email' 	=> $values['email']
						);
								
						if (null === ($subscription = $this->modx->getObject('NewsletterSubscriptions', $c))) {
							$subscription = $this->modx->newObject('NewsletterSubscriptions', $c);
						}
	
						$subscription->fromArray(array_merge($values, array(
							'active' => isset($this->properties['confirm']) ? 0 : 1
						)));
		
						$lists = $this->properties['lists'];

						if (isset($values['lists'])) {
							if (!is_array($values['lists'])) {
								$lists = $lists + explode(',', $values['lists']);
							} else {
								$lists = $lists + $values['lists'];
							}
						}
						
						$c = array(
							'primary' => 1
						);
						
						foreach ($this->modx->getCollection('NewsletterLists', $c) as $list) {
							$lists[] = $list->id;
						}
						
						
						foreach (array_filter(array_unique($lists)) as $list) {
							$c = array(
								'list_id'			=> $list,
								'subscription_id' 	=> $subscription->id
							);

							if (null === $subscription->getOne('NewsletterListsSubscriptions', $c)) {
								if (null !== ($list = $this->modx->newObject('NewsletterListsSubscriptions', $c))) {
									$subscription->addMany($list);
								}
							}
						}
						
						if (isset($this->properties['data'])) {
							foreach ($this->properties['data'] as $key) {
								if (isset($values[$key])) {
									if (is_array($values[$key])) {
										$subscription->setData($key, implode(',', $values[$key]));
									} else {
										$subscription->setData($key, $values[$key]);
									}
								} else {
									$subscription->setData($key, '');
								}
							}
						}
						
						if ($subscription->save()) {
							$this->modx->toPlaceholders(array(
								'subscription'	=> $subscription->toArray(),
								'token'			=> $values['token'],
								'last_id'		=> $subscription->id
							), rtrim($this->properties['placeholder'], '.'));
							
							if (isset($this->properties['confirm'])) {
								if (isset($this->properties['confirm']['url'])) {
									$url = $this->properties['confirm']['url'];
								} else {
									$url = $this->modx->getOption('newsletter.page_subscribe');
								}
								
								$this->modx->toPlaceholders(array(
									'subscribe_url'	=> $this->modx->makeUrl($url, null, array(
										'token'			=> $values['token'],
										'email'			=> $values['email']
									), 'full')
								), rtrim($this->properties['placeholder'], '.'));
								
								if (isset($this->properties['confirm']['successTpl'])) {
									$form->setScriptProperties(array(
										'tpl' => $this->properties['confirm']['successTpl']
									));
								}
								
								if (isset($this->properties['confirm']['email'])) {
									$email = array_merge(array(
										'to'		=> array($subscription->email => $subscription->name),
										'from'		=> array($this->config['sender_email'] => $this->config['sender_name']),
										'subject'	=> $this->modx->lexicon('newsletter.email_subscribe_confirmed_title')
									), $this->properties['confirm']['email']);

									if (!$form->extensions->invokeExtension('onSuccessPost', 'email', $email)) {
										return false;
									}
								}
							} else {
								if (isset($this->properties['successTpl'])) {
									$form->setScriptProperties(array(
										'tpl' => $this->properties['successTpl']
									));
								}
								
								if (isset($this->properties['email'])) {
									$email = array_merge(array(
										'to'		=> array($subscription->email => $subscription->name),
										'from'		=> array($this->config['sender_email'] => $this->config['sender_name']),
										'subject'	=> $this->modx->lexicon('newsletter.email_subscribe_confirmed_title')
									), $this->properties['email']);

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
		 * @access public.
		 * @return Boolean.
		 */
		public function getActionUnSubscribe() {
			if (null !== ($form = $this->getForm())) {
				switch ($this->properties['event']) {
					case 'onBeforePost':
						$form->setValues($this->modx->request->getParameters());
						
						$values = array_merge(array(
							'email'		=> '',
							'context'	=> $this->modx->context->key,
							'active'	=> 2,
						), $form->getValues());
						
						$c = array(
							'context' 	=> $values['context'],
							'email' 	=> $values['email']
						);
								
						if (null !== ($subscription = $this->modx->getObject('NewsletterSubscriptions', $c))) {							
							$subscription->fromArray($values);
							
							
							if ($subscription->save()) {
								return true;
							}
						}
						
						break;
					case 'onSuccessPost':
						$values = array_merge(array(
							'email'		=> '',
							'context'	=> $this->modx->context->key,
							'active'	=> 2
						), $form->getValues());
						
						$c = array(
							'context' 	=> $values['context'],
							'email' 	=> $values['email']
						);
								
						if (null !== ($subscription = $this->modx->getObject('NewsletterSubscriptions', $c))) {							
							$subscription->fromArray($values);
							
							
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