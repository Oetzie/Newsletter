<?php
/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$class = $modx->loadClass('NewsletterSnippetNewsletterForm', $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path') . 'components/newsletter/') . 'model/newsletter/snippets/', false, true);

if ($class) {
    $instance = new $class($modx);

    if ($instance instanceof NewsletterSnippets) {
        return $instance->run($event, $properties, $form);
    }
}

return '';