<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterList extends xPDOSimpleObject
{
    /**
     * @access public.
     * @param String $context.
     * @return Array.
     */
    public function getSubscriptions($context)
    {
        $criteria = $this->xpdo->newQuery('NewsletterSubscription');

        $criteria->select($this->xpdo->getSelectColumns('NewsletterSubscription', 'NewsletterSubscription'));

        $criteria->leftJoin('NewsletterListSubscription', 'NewsletterListSubscription', [
            'NewsletterListSubscription.subscription_id = NewsletterSubscription.id'
        ]);

        $criteria->where([
            'NewsletterSubscription.context'        => $context,
            'NewsletterListSubscription.list_id'    => $this->get('id')
        ]);

        return $this->xpdo->getCollection('NewsletterSubscription', $criteria);
    }
}
