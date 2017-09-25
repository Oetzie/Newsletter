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

	switch($modx->event->name) {
		case 'OnLoadWebDocument':
			if ($modx->loadClass('Newsletter', $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path').'components/newsletter/').'model/newsletter/', true, true)) {
                $newsletter = new Newsletter($modx);

        	    if ($newsletter instanceOf Newsletter) {
			        if (in_array($modx->resource->template, $modx->getOption('template', $newsletter->config, array()))) {
			            $parameters = $modx->request->getParameters();
			         
			            if (isset($parameters['newsletter'])) {
			                foreach (unserialize(str_rot13($parameters['newsletter'])) as $key => $value) {
			                    if (false !== strstr($key, 'subscribe')) {
			                    	$modx->setPlaceholder(str_replace('subscribe_', 'subscribe.', $key), $value);
			                    } else if (false !== strstr($key, 'newsletter')) {
			                    	$modx->setPlaceholder(str_replace('newsletter_', 'newsletter.', $key), $value);
			                    }
			                }
			            }
			        }
        	    }
			}

			break;
	}
	
	return;
	
?>