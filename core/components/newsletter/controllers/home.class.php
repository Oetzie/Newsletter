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

	class NewsletterHomeManagerController extends NewsletterManagerController {
		/**
		 * @acces public.
		 */
		public function loadCustomCssJs() {
			$this->addCss($this->modx->getOption('css_url', $this->newsletter->config).'mgr/newsletter.css');
			
			$this->addJavascript($this->modx->getOption('js_url', $this->newsletter->config).'mgr/widgets/home.panel.js');

			$this->addJavascript($this->modx->getOption('js_url', $this->newsletter->config).'mgr/widgets/newsletters.grid.js');
			$this->addJavascript($this->modx->getOption('js_url', $this->newsletter->config).'mgr/widgets/subscriptions.grid.js');
			$this->addJavascript($this->modx->getOption('js_url', $this->newsletter->config).'mgr/widgets/subscriptions-info.grid.js');
			$this->addJavascript($this->modx->getOption('js_url', $this->newsletter->config).'mgr/widgets/lists.grid.js');
			
			$this->addLastJavascript($this->modx->getOption('js_url', $this->newsletter->config).'mgr/sections/home.js');
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
			return $this->modx->getOption('templates_path', $this->newsletter->config).'home.tpl';
		}
	}

?>