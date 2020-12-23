<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterSubscriptionDataRemoveProcessor extends modProcessor {
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
    public function process() {
        $object = $this->modx->getObject($this->classKey, [
            'id' => $this->getProperty('id')
        ]);

        if ($object) {
            $data = $object->getData();

            if (isset($data[$this->getProperty('key')])) {
                unset($data[$this->getProperty('key')]);
            }

            $object->setData($data, false);

            if ($object->save()) {
                return $this->success('', $object->toArray());
            }

            return $this->failure();

        }

        return $this->failure($this->modx->lexicon('newsletter.subscription_data_error'));
    }
}

return 'NewsletterSubscriptionDataRemoveProcessor';
