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

	$newsletter = $modx->getService('newsletter', 'Newsletter', $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path').'components/newsletter/').'model/newsletter/');

	$subscribe = false; 

	switch($prefix) {
		case 'Before':
			$properties = array(
				'type'			=> 'complete',
				'values'		=> $modx->request->getParameters(),
				'resource'		=> $modx->getOption('newsletterRedirect', $form->extensionScriptProperties, false),
				'param'			=> $modx->getOption('param', $scriptProperties)
			);

			if (false === ($subscribe = $newsletter->subscribe($properties))) {
				$form->getValidator()->setBulkError('extension_newsletter_subscribe_confirm');
			}

			break;
		case 'After':
			if ($form->isValid()) {
				$properties = array(
					'type'			=> 'subscribe',
					'values'		=> $form->getValues(),
					'lists'			=> $modx->getOption('newsletterLists', $form->extensionScriptProperties),
					'resource'		=> $modx->getOption('newsletterRedirect', $form->extensionScriptProperties, false),
					'confirm'		=> $modx->getOption('newsletterConfirm', $form->extensionScriptProperties, $modx->getOption('confirm', $scriptProperties)),
					'param'			=> $modx->getOption('param', $scriptProperties)
				);

				if (false === ($subscribe = $newsletter->subscribe($properties))) {
					$form->getValidator()->setBulkError('extension_newsletter_subscribe');
				}
			}

			break;
	}

	return $subscribe;
	
?>