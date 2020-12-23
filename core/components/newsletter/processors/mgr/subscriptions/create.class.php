<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterSubscriptionCreateProcessor extends modObjectCreateProcessor
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

        $this->setDefaultProperties([
            'type' => 'admin'
        ]);

        if ($this->getProperty('active') === null) {
            $this->setProperty('active', 0);
        }

        if ($this->getProperty('token') === null) {
            $this->setProperty('token', md5(time()));
        }

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function beforeSave()
    {
        $this->modx->removeCollection('NewsletterListSubscription', [
            'subscription_id' => $this->object->get('id')
        ]);

        foreach ((array) $this->getProperty('lists') as $id) {
            $object = $this->modx->newObject('NewsletterListSubscription');

            if ($object) {
                $object->fromArray([
                    'list_id' => $id
                ]);

                $this->object->addMany($object);
            }
        }

        $this->object->set('edited', uniqid());

        return parent::beforeSave();
    }
}

return 'NewsletterSubscriptionCreateProcessor';
