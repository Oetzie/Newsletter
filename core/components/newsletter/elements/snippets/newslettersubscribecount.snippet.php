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

	if ($modx->loadClass('Newsletter', $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path').'components/newsletter/').'model/newsletter/', true, true)) {
        $newsletter = new Newsletter($modx);    
	
	    if ($newsletter instanceOf Newsletter) {
		   	if (false !== ($tpl = $modx->getOption('tpl', $scriptProperties, false))) {
		   	    $lists = $modx->getOption('lists', $scriptProperties);
		
				$output = array();
				
				foreach ($newsletter->getCount($lists) as $list) {
					$output[] = $newsletter->getTemplate($tpl, $list);
				}
		
				if (0 < count($output)) {
					if (false !== ($tplWrapper = $modx->getOption('tplWrapper', $scriptProperties, false))) {
						return $newsletter->getTemplate($tplWrapper, array(
							'output' => implode(PHP_EOL, $output)
						));
					} else {
						return implode(PHP_EOL, $output);
					}
				}		
			}
		}
	}
	
	return ' ';
	
?>