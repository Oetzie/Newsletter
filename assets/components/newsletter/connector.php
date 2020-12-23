<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once dirname(dirname(dirname(__DIR__))) . '/config.core.php';

require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$modx->getService('newsletter', 'Newsletter', $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path') . 'components/newsletter/') . 'model/newsletter/');

if ($modx->newsletter instanceof Newsletter) {
    $modx->request->handleRequest([
        'processors_path'   => $modx->newsletter->config['processors_path'],
        'location'          => ''
    ]);
}
