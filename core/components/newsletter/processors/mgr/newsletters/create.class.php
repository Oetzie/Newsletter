<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterNewsletterCreateProcessor extends modObjectCreateProcessor
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

        //if ($this->newsletter->hasPermission()) {
            if ($this->getProperty('hidden') === null) {
                $this->setProperty('hidden', 0);
            }
        //} else {
        //    $this->setProperty('hidden', 0);
        //}

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function beforeSave()
    {
        $resource = $this->modx->getObject('modResource', [
            'id'        => $this->object->get('resource_id'),
            'deleted'   => 0
        ]);

        if (!$resource) {
            $this->addFieldError('resource', $this->modx->lexicon('newsletter.newsletter_error_resource_id'));
        } else {
            if (!in_array($resource->get('template'), $this->modx->newsletter->config['templates'], false)) {
                $this->addFieldError('resource', $this->modx->lexicon('newsletter.newsletter_error_resource_template'));
            } else {
                $resource->set('cacheable', 0);

                $resource->save();
            }
        }

        return parent::beforeSave();
    }
}

return 'NewsletterNewsletterCreateProcessor';
