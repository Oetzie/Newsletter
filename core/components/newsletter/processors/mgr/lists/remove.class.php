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
	 
	class NewsletterListsRemoveProcessor extends modObjectRemoveProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NewsletterLists';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('newsletter:default');
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'newsletter.lists';
		
		/**
		 * @access public.
		 * @var Object.
		 */
		public $newsletter;
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->newsletter = $this->modx->getService('newsletter', 'Newsletter', $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path').'components/newsletter/').'model/newsletter/');

			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function beforeRemove() {
			if (1 == $this->object->primairy) {
				$this->failure($this->modx->lexicon('newsletter.lists_remove_primary_list.'));
			}
			
			return parent::beforeRemove();
		}
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function afterRemove() {
			if (1 != $this->object->primairy) {
				$this->modx->removeCollection('NewsletterListsNewsletter', array(
					'list_id' => $this->getProperty('id')
				));
				
				$this->modx->removeCollection('NewsletterListsSubscriptions', array(
					'list_id' => $this->getProperty('id')
				));
			}

			return parent::afterRemove();
		}
	}
	
	
	return 'NewsletterListsRemoveProcessor';
?>