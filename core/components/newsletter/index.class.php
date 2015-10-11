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

	abstract class NewsletterManagerController extends modExtraManagerController {
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
			require_once $this->modx->getOption('newsletters.core_path', null, $this->modx->getOption('core_path').'components/newsletter/').'/model/newsletter/newsletter.class.php';
			
			$this->newsletter = new Newsletter($this->modx);
			
			$this->addJavascript($this->newsletter->config['jsUrl'].'mgr/newsletter.js');
			$this->addHtml('<script type="text/javascript">
				Ext.onReady(function() {
					MODx.config.help_url = "http://rtfm.modx.com/extras/revo/'.$this->newsletter->getHelpUrl().'";
			
					Newsletter.config = '.$this->modx->toJSON(array_merge(array('admin' => $this->newsletter->hasPermission()), $this->newsletter->config)).';
				});
			</script>');
			
			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @return Array.
		 */
		public function getLanguageTopics() {
			return array('newsletter:default');
		}
		
		/**
		 * @acces public.
		 * @returns Boolean.
		 */	    
		public function checkPermissions() {
			return true;
		}
	}
		
	class IndexManagerController extends NewsletterManagerController {
		/**
		 * @acces public.
		 * @return String.
		 */
		public static function getDefaultController() {
			return 'home';
		}
	}

?>