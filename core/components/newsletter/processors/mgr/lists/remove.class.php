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
	 
	class ListsRemoveProcessor extends modObjectRemoveProcessor {
		/**
		 * @acces public.
		 * @var String.
		 */
		public $classKey = 'NewsletterLists';
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $languageTopics = array('newsletter:default');
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $objectType = 'newsletter.lists';
		
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
			require_once $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path').'components/newsletter/').'/model/newsletter/newsletter.class.php';
			
			$this->newsletter = new Newsletter($this->modx);

			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function beforeRemove() {
			if ($this->modx->getOption('primaryKey', $this->newsletter->config, 1) == $this->getProperty('id')) {
				$this->failure($this->modx->lexicon('newsletter.lists_remove_primary_list.'));
			}
			
			return parent::beforeRemove();
		}
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function afterRemove() {
			if ($this->modx->getOption('primaryKey', $this->newsletter->config, 1) == $this->getProperty('id')) {
				$this->modx->removeCollection('NewsletterListsNewsletter', array('list_id' => $this->getProperty('id')));
				$this->modx->removeCollection('NewsletterListsSubscriptions', array('list_id' => $this->getProperty('id')));
			}

			return parent::afterRemove();
		}
	}
	
	
	return 'ListsRemoveProcessor';
?>