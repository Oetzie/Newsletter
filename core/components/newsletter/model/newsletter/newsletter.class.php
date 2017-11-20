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

	class Newsletter {
		/**
		 * @access public.
		 * @var Object.
		 */
		public $modx;
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $config = array();
		
		/**
		 * @access public.
		 * @param Object $modx.
		 * @param Array $config.
		 */
		public function __construct(modX &$modx, array $config = array()) {
			$this->modx =& $modx;

			$corePath 		= $this->modx->getOption('newsletter.core_path', $config, $this->modx->getOption('core_path').'components/newsletter/');
			$assetsUrl 		= $this->modx->getOption('newsletter.assets_url', $config, $this->modx->getOption('assets_url').'components/newsletter/');
			$assetsPath 	= $this->modx->getOption('newsletter.assets_path', $config, $this->modx->getOption('assets_path').'components/newsletter/');
		
			$this->config = array_merge(array(
				'namespace'				=> $this->modx->getOption('namespace', $config, 'newsletter'),
				'lexicons'				=> array('newsletter:default', 'newsletter:site', 'site:newsletter'),
				'base_path'				=> $corePath,
				'core_path' 			=> $corePath,
				'model_path' 			=> $corePath.'model/',
				'processors_path' 		=> $corePath.'processors/',
				'elements_path' 		=> $corePath.'elements/',
				'chunks_path' 			=> $corePath.'elements/chunks/',
				'cronjobs_path' 		=> $corePath.'elements/cronjobs/',
				'plugins_path' 			=> $corePath.'elements/plugins/',
				'snippets_path' 		=> $corePath.'elements/snippets/',
				'templates_path' 		=> $corePath.'templates/',
				'assets_path' 			=> $assetsPath,
				'js_url' 				=> $assetsUrl.'js/',
				'css_url' 				=> $assetsUrl.'css/',
				'assets_url' 			=> $assetsUrl,
				'connector_url'			=> $assetsUrl.'connector.php',
				'version'				=> '1.6.0',
				'branding_url'			=> $this->modx->getOption('newsletter.branding_url', null, ''),
				'branding_help_url'		=> $this->modx->getOption('newsletter.branding_url_help', null, ''),
				'has_permission'		=> $this->hasPermission(),
				'template'				=> explode(',', $this->modx->getOption('newsletter.template', null, '')),
				'sender_name'			=> $this->modx->getOption('newsletter.name', null, $this->modx->getOption('site_name')),
				'sender_email'			=> $this->modx->getOption('newsletter.email', null, $this->modx->getOption('emailsender')),
				'token'					=> $this->modx->getOption('newsletter.token', null, md5(time())),
				'context'				=> $this->getContexts()
			), $config);
		
			$this->modx->addPackage('newsletter', $this->config['model_path']);
			
			if (is_array($this->config['lexicons'])) {
				foreach ($this->config['lexicons'] as $lexicon) {
					$this->modx->lexicon->load($lexicon);
				}
			} else {
				$this->modx->lexicon->load($this->config['lexicons']);
			}
		}
		
		/**
		 * @access public.
		 * @return String|Boolean.
		 */
		public function getHelpUrl() {
		    if (!empty($this->config['branding_help_url'])) {
                return $this->config['branding_help_url'].'?v=' . $this->config['version'];
            }

            return false;
		}

        /**
         * @access public.
         * @return String|Boolean.
         */
        public function getBrandingUrl() {
            if (!empty($this->config['branding_url'])) {
                return $this->config['branding_url'];
            }

            return false;
        }
        
        /**
		 * @access public.
		 * @return Boolean.
		 */
		public function hasPermission() {
			return $this->modx->hasPermission('newsletter_settings');
		}
		
		/**
		 * @access private.
		 * @return Boolean.
		 */
		private function getContexts() {
			return 1 == $this->modx->getCount('modContext', array(
				'key:!=' => 'mgr'
			));
		}
		
		/**
		 * @access public.
		 * @param String $tpl.
		 * @param Array $properties.
		 * @param String $type.
		 * @return String.
		 */
		public function getTemplate($template, $properties = array(), $type = 'CHUNK') {
			if (0 === strpos($template, '@')) {
				$type 		= substr($template, 1, strpos($template, ':') - 1);
				$template	= substr($template, strpos($template, ':') + 1, strlen($template));
			}
			
			switch (strtoupper($type)) {
				case 'INLINE':
					$chunk = $this->modx->newObject('modChunk', array(
						'name' => $this->config['namespace'].uniqid()
					));
				
					$chunk->setCacheable(false);
				
					$output = $chunk->process($properties, ltrim($template));
				
					break;
				case 'CHUNK':
					$output = $this->modx->getChunk(ltrim($template), $properties);
				
					break;
			}
			
			return $output;
		}
				
		/**
		 * @access public.
		 * @param String $lists.
		 * @return Integer.
		 */
		public function getCount($lists) {
			$count = array();
			
			if (is_string($lists)) {
				$lists = explode(',', $lists);
			}
			
			foreach ($lists as $id) {
				if (null !== ($list = $this->modx->getObject('NewsletterLists', array('id' => $id)))) {
					$count[$id] = array(
						'count' => $list->getSubscriptionsCount($this->modx->resource->context_key)
					);
				}
			}
				
			return $count;
		}
		
		/**
		 * @access public.
		 * @param String|Array $input.
		 * @param String $type.
		 * @return String|Array.
		 */
		public function hashValues($input = array(), $type = 'encrypt') {
		    if ('decode' == $type) {
			    return unserialize(str_rot13($input));
		    } else {
			    return str_rot13(serialize($input));
		    }
		}
    }
	
?>