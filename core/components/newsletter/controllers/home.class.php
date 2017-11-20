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

    require_once dirname(dirname(__FILE__)).'/index.class.php';

	class NewsletterHomeManagerController extends NewsletterManagerController {
		/**
		 * @access public.
		 */
		public function loadCustomCssJs() {
			$this->addCss($this->newsletter->config['css_url'].'mgr/newsletter.css');
			
			$this->addJavascript($this->newsletter->config['js_url'].'mgr/widgets/home.panel.js');

			$this->addJavascript($this->newsletter->config['js_url'].'mgr/widgets/newsletters.grid.js');
			$this->addJavascript($this->newsletter->config['js_url'].'mgr/widgets/subscriptions.grid.js');
			$this->addJavascript($this->newsletter->config['js_url'].'mgr/widgets/subscriptions.data.grid.js');
			$this->addJavascript($this->newsletter->config['js_url'].'mgr/widgets/lists.grid.js');
			
			$this->addLastJavascript($this->newsletter->config['js_url'].'mgr/sections/home.js');
		}
		
		/**
		 * @access public.
		 * @return String.
		 */
		public function getPageTitle() {
			return $this->modx->lexicon('newsletter');
		}
		
		/**
		* @access public.
		* @return String.
		*/
		public function getTemplateFile() {
			return $this->newsletter->config['templates_path'].'home.tpl';
		}
	}

?>