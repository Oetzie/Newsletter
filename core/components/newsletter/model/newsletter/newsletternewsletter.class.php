<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterNewsletter extends xPDOSimpleObject
{
    /**
     * @access public.
     * @param String $type.
     * @return Array.
     */
    public function getQueue($type = 'send')
    {
        $criteria = $this->xpdo->newQuery('NewsletterNewsletterQueue');

        $criteria->where([
            'newsletter_id' => $this->get('id'),
            'type'          => $type,
            'status'        => 0
        ]);

        if ($type === 'test') {
            $criteria->sortby('id', 'ASC');
        } else {
            $criteria->where([
                'date:>=' => date('Y-m-d H:i:s')
            ]);

            $criteria->sortby('date', 'ASC');
        }

        return $this->xpdo->getCollection('NewsletterNewsletterQueue', $criteria);
    }

    /**
     * @access public.
     * @param Integer $limit.
     * @param String $type.
     * @return Array.
     */
    public function getPrevQueue($limit = 1, $type = 'send')
    {
        $criteria = $this->xpdo->newQuery('NewsletterNewsletterQueue');

        $criteria->where([
            'newsletter_id' => $this->get('id'),
            'date:<='       => date('Y-m-d H:i:s')
        ]);

        if ($type !== null) {
            $criteria->where([
                'type' => $type
            ]);
        }

        $criteria->sortby('date', 'DESC');

        if ((int) $limit !== 0) {
            $criteria->limit($limit);
        }

        return $this->xpdo->getCollection('NewsletterNewsletterQueue', $criteria);
    }

    /**
     * @access public.
     * @param Integer $limit.
     * @param String $type.
     * @return Array.
     */
    public function getNextQueue($limit = 1, $type = 'send')
    {
        $criteria = $this->xpdo->newQuery('NewsletterNewsletterQueue');

        $criteria->where([
            'newsletter_id' => $this->get('id'),
            'date:>='       => date('Y-m-d H:i:s'),
            'status'        => 0
        ]);

        if ($type !== null) {
            $criteria->where([
                'type' => $type
            ]);
        }

        $criteria->sortby('date', 'ASC');

        if ((int) $limit !== 0) {
            $criteria->limit($limit);
        }

        return $this->xpdo->getCollection('NewsletterNewsletterQueue', $criteria);
    }
}
