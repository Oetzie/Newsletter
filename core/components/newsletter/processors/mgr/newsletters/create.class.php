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

	class NewsletterNewslettersCreateProcessor extends modObjectCreateProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NewsletterNewsletters';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('newsletter:default', 'newsletter:site', 'site:newsletter');
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'newsletter.newsletters';
		
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
			
			if ($this->newsletter->hasPermission()) {
				if (null === $this->getProperty('hidden')) {
					$this->setProperty('hidden', 0);
				}
			} else {
				$this->setProperty('hidden', 0);
			}

			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function beforeSave() {
			$c = array(
				'id' 		=> $this->getProperty('resource_id'),
				'deleted' 	=> 0
			);
			
			if (null === ($resource = $this->modx->getObject('modResource', $c))) {
				$this->addFieldError('resource', $this->modx->lexicon('newsletter.newsletter_error_resource_id'));
			} else {
				if (!in_array($resource->template, $this->modx->getOption('template', $this->newsletter->config, array()))) {
					$this->addFieldError('resource', $this->modx->lexicon('newsletter.newsletter_error_resource_template'));
				} else {
					$resource->fromArray(array(
						'cacheable'	=> 0
					));
								
					$resource->save();
				}
			}

			return parent::beforeSave();
		}
	}
	
	return 'NewsletterNewslettersCreateProcessor';
?>