<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterNewsletterQueue extends xPDOSimpleObject
{
    /**
     * @access public.
     * @return Array.
     */
    public function getLists()
    {
        $criteria = $this->xpdo->newQuery('NewsletterList');

        $criteria->select($this->xpdo->getSelectColumns('NewsletterList', 'NewsletterList'));

        $criteria->leftJoin('NewsletterNewsletterQueueList', 'NewsletterNewsletterQueueList', [
            'NewsletterNewsletterQueueList.list_id = NewsletterList.id'
        ]);

        $criteria->where([
            'NewsletterNewsletterQueueList.queue_id' => $this->get('id'),
        ]);

        return $this->xpdo->getCollection('NewsletterList', $criteria);
    }
}
