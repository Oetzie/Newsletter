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

	class NewsletterHomeManagerController extends NewsletterManagerController {
		/**
		 * @acces public.
		 * @param Array $scriptProperties.
		 */
		public function process(array $scriptProperties = array()) {
			$this->addHtml('<script type="text/javascript">
				Ext.onReady(function() {
					Newsletter.config.groups = '.$this->modx->toJSON($this->getGroups()).';
				});
			</script>');
		}
		
		/**
		 * @acces public.
		 */
		public function loadCustomCssJs() {
			$this->addCss($this->newsletter->config['cssUrl'].'mgr/newsletter.css');
			$this->addJavascript($this->newsletter->config['jsUrl'].'mgr/widgets/home.panel.js');
			$this->addJavascript($this->newsletter->config['jsUrl'].'mgr/widgets/newsletters.grid.js');
			$this->addJavascript($this->newsletter->config['jsUrl'].'mgr/widgets/subscriptions.grid.js');
			$this->addJavascript($this->newsletter->config['jsUrl'].'mgr/widgets/groups.grid.js');
			$this->addLastJavascript($this->newsletter->config['jsUrl'].'mgr/sections/home.js');
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function getPageTitle() {
			return $this->modx->lexicon('newsletter');
		}
		
		/**
		* @acces public.
		* @return String.
		*/
		public function getTemplateFile() {
			return $this->newsletter->config['templatesPath'].'home.tpl';
		}
		
		/**
		 * @acces public.
		 * @return Array.
		 */
		public function getGroups() {
			$groups = array();
			
			foreach ($this->modx->getCollection('Groups') as $key => $value) {
				$groups[] = $value->toArray();
			}
			
			return $groups;
		}
	}

?>