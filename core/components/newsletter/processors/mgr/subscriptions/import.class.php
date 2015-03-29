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

	class SubscriptionsImportProcessor extends modObjectImportProcessor {
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
		public $objectType = 'newsletter.subscriptions';
		
		/**
		 * @acces public.
		 * @return mixed.
		 */
		public function beforeSave() {
			$newGroupIDs = array();
			
			if (1 == $this->getProperty('groups')) {
				if (isset($this->xml->groups->group)) {
					foreach ($this->xml->groups->group as $key => $value) {
						$group = $this->modx->newObject('NewsletterGroups');
						
						if (null !== $group) {
							$group->fromArray(array(
								'context'		=> (string) $value->context,
								'name'			=> (string) $value->name,
								'description'	=> (string) $value->description,
								'active'		=> (string) $value->active
							));
							
							$group->save();
							
							$newGroupIDs[(string) $value->id] = $group->id;
						}
					}
				}
			}
			
			if (1 == $this->getProperty('subscriptions')) {
				if (isset($this->xml->subscriptions->subscription)) {
					foreach ($this->xml->subscriptions->subscription as $key => $value) {
						if (null === ($subscription = $this->modx->getObject($this->classKey, array('email' => (string) $value->email)))) {
							$subscription = $this->modx->newObject($this->classKey);
						}
					
						if (null !== $subscription) {
							$subscription->fromArray(array(
								'name'		=> (string) $value->name,
								'email'		=> (string) $value->email,
								'active'	=> (string) $value->active,
								'confirm'	=> (string) $value->confirm
							));
						
							$groups = array();
						
							foreach ($value->groups->group as $subKey => $subValue) {
								if (null !== ($group = $this->modx->newObject('NewsletterSubscriptionsGroups'))) {
									$group->fromArray(array(
										'group_id'	=> $this->modx->getOption((string) $subValue, $newGroupIDs, (string) $subValue)
									));
						
									$groups[] = $group;
								}
							}
						
							$subscription->addMany($groups);
							$subscription->save();
						}
					}
				}
			}
			
			return parent::beforeSave();
		}
	}

	return 'SubscriptionsImportProcessor';
	
?>