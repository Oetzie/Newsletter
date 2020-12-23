<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterListCreateProcessor extends modObjectCreateProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'NewsletterList';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['newsletter:default'];

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'newsletter.list';

    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('newsletter', 'Newsletter', $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path') . 'components/newsletter/') . 'model/newsletter/');

        if ($this->getProperty('active') === null) {
            $this->setProperty('active', 0);
        }

        //if ($this->newsletter->hasPermission()) {
            if ($this->getProperty('primary') === null) {
                $this->setProperty('primary', 0);
            }
        //} else {
        //    $this->setProperty('primary', 0);
        //}

        //if ($this->newsletter->hasPermission()) {
            if ($this->getProperty('hidden') === null) {
                $this->setProperty('hidden', 0);
            }
        //} else {
        //    $this->setProperty('hidden', 0);
        //}

        return parent::initialize();
    }
}

return 'NewsletterListCreateProcessor';
