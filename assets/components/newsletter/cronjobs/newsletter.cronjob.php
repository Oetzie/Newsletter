<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx = new modX();
$modx->initialize('mgr');

$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

/*
 * Put the options in the $options variable.
 * We use getopt for CLI executions and $_GET for http executions.
 */
$options = [];

if (XPDO_CLI_MODE) {
    $options = getopt('', ['debug', 'id::', 'hash::']);
} else {
    $options = $_GET;
}

if (!isset($options['hash']) || $options['hash'] !== $modx->getOption('newsletter.cronjob_hash')) {
    $modx->log(modX::LOG_LEVEL_INFO, 'ERROR:: Cannot initialize service, no valid hash provided.');

    exit();
}

$service = $modx->getService('newslettercronjob', 'NewsletterCronjob', $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path') . 'components/newsletter/') . 'model/newsletter/');

if ($service instanceof Newsletter) {
    if (isset($options['debug'])) {
        $service->setDebugMode(true);
    }

    if (isset($options['id'])) {
        $service->setNewsletterId($options['id']);
    }

    $service->run();
} else {
    $modx->log(modX::LOG_LEVEL_INFO, 'ERROR:: Cannot initialize service.');
}
