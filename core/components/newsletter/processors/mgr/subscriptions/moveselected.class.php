<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterSubscriptionMoveSelectedProcessor extends modProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'NewsletterSubscription';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['newsletter:default'];

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'newsletter.subscription';

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
    public function process()
    {
        foreach (explode(',', $this->getProperty('ids')) as $id) {
            $object = $this->modx->getObject($this->classKey, [
                'id' => $id
            ]);

            if ($object) {
                foreach ((array) $this->getProperty('lists') as $id) {
                    $this->modx->removeCollection('NewsletterListSubscription', [
                        'subscription_id'   => $object->get('id'),
                        'list_id'           => $id
                    ]);

                    if ($this->getProperty('type') !== 'remove') {
                        $list = $this->modx->newObject('NewsletterListSubscription', [
                            'list_id' => $id
                        ]);

                        if ($list) {
                            $object->addMany($list);
                        }
                    }
                }

                $object->set('edited', uniqid());
                $object->save();
            }
        }

        return $this->success();
    }
}

return 'NewsletterSubscriptionMoveSelectedProcessor';
