<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterSubscription extends xPDOSimpleObject
{
    /**
     * @access public.
     * @return Array.
     */
    public function getLists()
    {
        $criteria = $this->xpdo->newQuery('NewsletterList');

        $criteria->select($this->xpdo->getSelectColumns('NewsletterList', 'NewsletterList'));

        $criteria->leftJoin('NewsletterListSubscription', 'NewsletterListSubscription', [
            'NewsletterListSubscription.list_id = NewsletterList.id'
        ]);

        $criteria->where([
            'NewsletterListSubscription.subscription_id' => $this->get('id')
        ]);

        return $this->xpdo->getCollection('NewsletterList', $criteria);
    }

    /**
     * @access public.
     * @param Array $value.
     * @param Boolean $merge.
     */
    public function setData(array $value = [], $merge = true)
    {
        if ($merge) {
            $this->set('data', json_encode(array_merge($this->getData(), $value)));
        } else {
            $this->set('data', json_encode($value));
        }
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getData()
    {
        $data = json_decode($this->get('data'), true);

        if ($data) {
            return $data;
        }

        return [];
    }
}
