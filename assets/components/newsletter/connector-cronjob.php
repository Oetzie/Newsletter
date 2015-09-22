<?php

	/**
	 * Newsletter
	 *
	 * Copyright 2014 by Oene Tjeerd de Bruin <info@oetzie.nl>
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

	define('MODX_REQP', false);
	  
	require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
	require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
	require_once MODX_CONNECTORS_PATH.'index.php';

	if ($_REQUEST['token'] == $modx->getOption('newsletter_token', null, md5(time()))) {
		if ($modx->user->hasSessionContext($modx->context->get('key'))) {
			$_SERVER['HTTP_MODAUTH'] = $_SESSION["modx.{$modx->context->get('key')}.user.token"];
			$_REQUEST['HTTP_MODAUTH'] = $_SERVER['HTTP_MODAUTH'];
		} else {
			$_SESSION["modx.{$modx->context->get('key')}.user.token"] = 0;
			$_SERVER['HTTP_MODAUTH'] = 0;
		}
	}
	
	$_REQUEST['action'] = 'mgr/send';
	
	$corePath = $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path').'components/newsletter/');
	
	require_once $corePath.'model/newsletter/newsletter.class.php';
	
	$modx->newsletter = new Newsletter($modx);
	
	$modx->lexicon->load('newsletter:default');
	
	$path = $modx->getOption('processorsPath', $modx->newsletter->config, $corePath.'processors/');
	
	$modx->request->handleRequest(array(
		'processors_path' 	=> $path,
		'location' 			=> ''
	));
	
?>