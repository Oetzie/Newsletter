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

	class SubscriptionsMoveSelectedProcessor extends modProcessor {
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
		 * @var Object.
		 */
		public $newsletter;
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->newsletter = $this->modx->getService('newsletter', 'Newsletter', $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path').'components/newsletter/').'model/newsletter/');

			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function process() {
			foreach (explode(',', $this->getProperty('ids')) as $key => $value) {
				$criteria = array('id' => $value);
				
				if (false !== ($object = $this->modx->getObject($this->classKey, $criteria))) {
					if (null !== ($lists = $this->getProperty('lists'))) {
						if ('remove' == $this->getProperty('type')) {
							foreach ($lists as $id) {
								$this->modx->removeCollection('NewsletterListsSubscriptions', array(
									'subscription_id' 	=> $object->id,
									'list_id'			=> $id
								));
							}
						} else {
							$existingLists = array();
							
							foreach ($object->getMany('NewsletterListsSubscriptions') as $existingList) {
								$existingLists[$existingList->list_id] = $existingList->list_id;
							}
							
							foreach ($lists as $id) {
								if (!isset($existingLists[$id])) {
									if (null !== ($list = $this->modx->newObject('NewsletterListsSubscriptions', array('list_id' => $id)))) {
										$object->addMany($list);
									}
								}	
							}
							
							$object->save();
						}
					}
				}
			}
			
			return $this->success();
		}
	}

	return 'SubscriptionsMoveSelectedProcessor';

?>