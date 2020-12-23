<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterSubscriptionDataCreateProcessor extends modProcessor
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

        if (($key = $this->getProperty('key')) !== null) {
            $this->setProperty('key', strtolower(str_replace([' ', '-'], '_', $key)));
        }

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function process()
    {
        $object = $this->modx->getObject($this->classKey, [
            'id' => $this->getProperty('id')
        ]);

        if ($object) {
            $key = $this->getProperty('key');

            if (!preg_match('/^([a-zA-Z0-9\_]+)$/i', $key)) {
                $this->addFieldError('key', $this->modx->lexicon('newsletter.subscription_data_error_character'));
            } else {
                $object->setData([
                    $key => $this->getProperty('content')
                ]);

                if ($object->save()) {
                    return $this->success('', $object->toArray());
                }
            }

            return $this->failure();
        }

        return $this->failure($this->modx->lexicon('newsletter.subscription_data_error'));
    }
}

return 'NewsletterSubscriptionDataCreateProcessor';
