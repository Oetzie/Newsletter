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
        	$unsubscribe = false; 
        
        	switch($prefix) {
        		case 'Before':
        			$properties = array(
        				'type'			=> 'confirm',
        				'values'		=> $modx->request->getParameters(),
        				'confirmParam'	=> $modx->getOption('newsletterConfirmParam', $form->properties, $modx->getOption('param', $scriptProperties)),
        				'success'       => $modx->getOption('newsletterSuccess', $form->properties, false)
        			);
        
        			if (false === ($unsubscribe = $newsletter->unsubscribe($properties))) {
        				$form->getValidator()->setBulkOutput($modx->lexicon('newsletter.form_unsubscribe_error_confirm'));
        			}
        			break;
        		case 'After':
        			if ($form->getValidator()->isValid()) {
        				$properties = array(
        					'values'		=> $form->getValues(),
        					'lists'			=> $modx->getOption('newsletterLists', $form->properties),
        					'confirmParam'	=> $modx->getOption('newsletterConfirmParam', $form->properties, $modx->getOption('param', $scriptProperties)),
        					'success'       => $modx->getOption('newsletterSuccess', $form->properties, false)
        				);
        
        				if (false === ($unsubscribe = $newsletter->unsubscribe($properties))) {
        					$form->getValidator()->setBulkOutput($modx->lexicon('newsletter.form_unsubscribe_error'));
        				}
        			}
        
        			break;
        	}
        
        	return $unsubscribe;
	    }
    }
    
    return false;
    
?>