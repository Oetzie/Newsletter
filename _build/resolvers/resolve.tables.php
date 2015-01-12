<?php

	if ($object->xpdo) {
	    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
	        case xPDOTransport::ACTION_INSTALL:
	            $modx =& $object->xpdo;
	            $modx->addPackage('newsletter', $modx->getOption('newsletter.core_path', null, $modx->getOption('core_path').'components/newsletter/').'model/');
	
	            $manager = $modx->getManager();
	
				$manager->createObjectContainer('NewsletterNewsletters');
	            $manager->createObjectContainer('NewsletterSubscriptions');
	            $manager->createObjectContainer('NewsletterSubscriptionsGroups');
	            $manager->createObjectContainer('NewsletterGroups');
	
	            break;
	        case xPDOTransport::ACTION_UPGRADE:
	            break;
	    }
	}
	
	return true;