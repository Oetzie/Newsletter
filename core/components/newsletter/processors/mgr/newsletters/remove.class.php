<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterNewsletterRemoveProcessor extends modObjectRemoveProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'NewsletterNewsletter';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['newsletter:default'];

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'newsletter.newsletter';

    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('newsletter', 'Newsletter', $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path') . 'components/newsletter/') . 'model/newsletter/');

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function afterRemove()
    {
        $queues = $this->modx->getCollection('NewsletterNewsletterQueue', [
            'newsletter_id' => $this->object->get('id')
        ]);

        foreach ($queues as $queue) {
            $this->modx->removeCollection('NewsletterNewsletterQueueList', [
                'queue_id' => $queue->get('id')
            ]);

            $queue->remove();
        }

        return parent::afterRemove();
    }
}

return 'NewsletterNewsletterRemoveProcessor';
