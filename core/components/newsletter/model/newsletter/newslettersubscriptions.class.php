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
	 
	class NewsletterSubscriptions extends xPDOSimpleObject {
		/**
		 * @acces public.
		 * @param String $key.
		 * @return Array.
		 */
		public function getLists($key = false) {
			$output = array();
		
			foreach ($this->getMany('NewsletterListsSubscriptions') as $list) {
				if (null !== ($list = $list->getOne('NewsletterLists'))) {
					if ($key) {
						$output[] = $list->id;
					} else {
						$output[] = $list;
					}
				}
			}
			
			if ($key) {
				return implode(',', $output);
			}

			return $output;
		}
		
		/**
		 * @access public.
		 * @param String $key.
		 * @return Mixed.
		 */
		public function getData($key = null) {
			if (null !== ($data = $this->xpdo->fromJSON($this->data))) {
				if (is_array($data)) {
					if (null !== $key) {
						if (isset($data[$key])) {
							return $data[$key];
						}
						
						return false;
					}
					
					return $data;
				}
			}
			
			return array();
		}
		
		/**
		 * @access public.
		 * @param String $key.
		 * @param Mixed $value.
		 * @return Boolean.
		 */
		public function setData($key, $value = null) {
			if (null === $value) {
				$this->fromArray(array(
					'data' =>  $this->xpdo->toJSON($key)
				));
			} else {
				$this->fromArray(array(
					'data' =>  $this->xpdo->toJSON(array_merge($this->getData(), array(
						$key => $value
					)))
				));
			}

			return true;
		}
		
		/**
		 * @access public.
		 * @param String $key.
		 * @return Boolean.
		 */
		public function removeData($key) {
			$data = $this->getData();
			
			if (isset($data[$key])) {
				unset($data[$key]);
			}
			
			return $this->setData($data);
		}
		
		/**
		 * @access public.
		 * @param String key.
		 * @return Boolean.
		 */
		public function isData($key) {
			$data = $this->getData();
			
			if (isset($data[$key])) {
				return true;
			}
			
			return false;
		}
	}
	
?>