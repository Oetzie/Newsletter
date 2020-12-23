<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UPGRADE:
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modx->addPackage('newsletter', $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path') . 'components/newsletter/') . 'model/');

            $manager = $modx->getManager();

            $manager->createObjectContainer('NewsletterNewsletter');
            $manager->createObjectContainer('NewsletterNewsletterQueue');
            $manager->createObjectContainer('NewsletterNewsletterQueueList');
            $manager->createObjectContainer('NewsletterSubscription');
            $manager->createObjectContainer('NewsletterList');
            $manager->createObjectContainer('NewsletterListSubscription');

            break;
    }
}

return true;
