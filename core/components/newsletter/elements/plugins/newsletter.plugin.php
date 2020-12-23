<?php
/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

if (in_array($modx->event->name, ['OnLoadWebDocument'], true)) {
    $instance = $modx->getService('newsletterplugins', 'NewsletterPlugins', $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path') . 'components/newsletter/') . 'model/newsletter/');

    if ($instance instanceof NewsletterPlugins) {
        $method = lcfirst($modx->event->name);

        if (method_exists($instance, $method)) {
            $instance->$method($scriptProperties);
        }
    }
}