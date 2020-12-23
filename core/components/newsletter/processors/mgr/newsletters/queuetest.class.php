<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterNewsletterQueueTestProcessor extends modObjectUpdateProcessor
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
    public function beforeSave()
    {
        $resource = $this->modx->getObject('modResource', [
            'id'        => $this->object->get('resource_id'),
            'published' => 1,
            'deleted'   => 0
        ]);

        if (!$resource) {
            $this->addFieldError('name', $this->modx->lexicon('newsletter.newsletter_error_resource_id'));
        } else {
            if (in_array($resource->template, $this->modx->newsletter->config['templates'], false)) {
                $resource->fromArray([
                    'cacheable' => 0
                ]);

                if ($resource->save()) {
                    $object = $this->modx->newObject('NewsletterNewsletterQueue', [
                        'type'          => 'test',
                        'newsletter_id' => $this->object->get('id'),
                        'emails'        => $this->getProperty('emails')
                    ]);

                    if ($object->save()) {
                        foreach ((array) $this->getProperty('lists') as $id) {
                            $list = $this->modx->newObject('NewsletterNewsletterQueueList', [
                                'list_id' => $id
                            ]);

                            if ($list) {
                                $object->addMany($list);
                            }
                        }

                        $object->save();
                    }
                }
            } else {
                $this->addFieldError('name', $this->modx->lexicon('newsletter.newsletter_error_resource_template'));
            }
        }

        return parent::beforeSave();
    }
}

return 'NewsletterNewsletterQueueTestProcessor';
