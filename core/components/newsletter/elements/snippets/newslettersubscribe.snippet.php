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
        	$subscribe = false;
        	
        	switch($prefix) {
        	    case 'Before':
        			$properties = array(
        				'type'			=> 'complete',
        				'values'		=> $modx->request->getParameters(),
        				'resource'		=> $modx->getOption('newsletterRedirect', $form->properties, false),
        				'param'			=> $modx->getOption('param', $scriptProperties)
        			);
        
        			if (false === ($subscribe = $newsletter->subscribe($properties))) {
        				$form->getValidator()->setBulkOutput($modx->lexicon('newsletter.subscribe_error_confirm'));
        			}
        
        			break;
        	    case 'After':
        	        if ($form->getValidator()->isValid()) {
        	            $properties = array(
        					'type'			=> 'subscribe',
        					'values'		=> $form->getValues(),
        					'lists'			=> $modx->getOption('newsletterLists', $form->properties),
        					'info'          => $modx->getOption('newsletterInfo', $form->properties, ''),
        					'resource'		=> $modx->getOption('newsletterRedirect', $form->properties, false),
        					'confirm'		=> $modx->getOption('newsletterConfirm', $form->properties, $modx->getOption('confirm', $scriptProperties)),
        					'customValues'  => $modx->getOption('newsletterCustomValues', $form->properties, ''),
        					'param'			=> $modx->getOption('param', $scriptProperties)
        				);
        				
        				if (false === ($subscribe = $newsletter->subscribe($properties))) {
        					$form->getValidator()->setBulkOutput($modx->lexicon('newsletter.subscribe_error'));
        				}
        	        }
        	        
        	        break;
        	}

	        return $subscribe;
	    }
    }
    
    return false;
    
?>