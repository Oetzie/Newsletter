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

	abstract class NewsletterManagerController extends modExtraManagerController {
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
			
			$this->addJavascript($this->newsletter->config['js_url'].'mgr/newsletter.js');
			
			$this->addHtml('<script type="text/javascript">
				Ext.onReady(function() {
					MODx.config.help_url = "'.$this->newsletter->getHelpUrl().'";
					
					Newsletter.config = '.$this->modx->toJSON(array_merge($this->newsletter->config, array(
                        'branding_url'          => $this->newsletter->getBrandingUrl(),
                        'branding_url_help'     => $this->newsletter->getHelpUrl()
                    ))).';
				});
			</script>');
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Array.
		 */
		public function getLanguageTopics() {
			return $this->newsletter->config['lexicons'];
		}
		
		/**
		 * @access public.
		 * @returns Boolean.
		 */	    
		public function checkPermissions() {
			return $this->modx->hasPermission('newsletter');
		}
	}
		
	class IndexManagerController extends NewsletterManagerController {
		/**
		 * @access public.
		 * @return String.
		 */
		public static function getDefaultController() {
			return 'home';
		}
	}

?>