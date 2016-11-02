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

	class NewsletterNewslettersSendProcessor extends modObjectUpdateProcessor {
		/**
		 * @acces public.
		 * @var String.
		 */
		public $classKey = 'NewsletterNewsletters';
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $languageTopics = array('newsletter:default');
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $objectType = 'newsletter.newsletters';
		
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

			if (1 == $this->getProperty('newsletter_type')) {
				$this->setProperty('send_status', 1);
			} else if (2 == $this->getProperty('newsletter_type')) {
				$this->setProperty('send_status', 1);
			} else if (3 == $this->getProperty('newsletter_type')) {
				$this->setProperty('send_status', 0);
			}
			
			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
	    public function beforeSave() {
		    if (1 == $this->modx->getOption('site_status')) {
			    if (null !== ($resource = $this->object->getNewsletterResource())) {
				    if (in_array($resource->template, $this->modx->getOption('template', $this->newsletter->config, array()))) {
					    $resource->fromArray(array(
						    'published'		=> 1,
							'cacheable'		=> 0
						));
									
						$resource->save();
						
						$this->modx->removeCollection('NewsletterListsNewsletters', array(
					    	'newsletter_id' => $this->getProperty('id')
					    ));
					    
						if (null !== ($lists = $this->getProperty('lists'))) {
							foreach ($lists as $id) {
								if (null !== ($list = $this->modx->newObject('NewsletterListsNewsletters', array('list_id' => $id)))) {
									$this->object->addMany($list);
								}
							}
						}
					} else {
						$this->addFieldError('newsletter_type', $this->modx->lexicon('newsletter.newsletter_send_error_template_desc'));
					}
			    } else {
				    $this->addFieldError('newsletter_type', $this->modx->lexicon('newsletter.newsletter_send_error_resource_desc'));
			    }
			} else {
				$this->failure($this->modx->lexicon('newsletter.newsletter_send_error_site_status_desc'));
			}
			
			return parent::beforeSave();
		}
	}
	
	return 'NewsletterNewslettersSendProcessor';
	
?>