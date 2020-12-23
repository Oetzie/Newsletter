<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterNewsletterQueueSendProcessor extends modObjectUpdateProcessor
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

        if (($days = $this->getProperty('days')) !== null) {
            $this->setProperty('days', implode(',', $days));
        }

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
                if (strtotime($this->getProperty('date')) < strtotime(date('d-m-Y'))) {
                    $this->addFieldError('date', $this->modx->lexicon('newsletter.newsletter_error_date'));
                } else if (strtotime($this->getProperty('date') . ' ' . $this->getProperty('time')) < strtotime(date('d-m-Y H:i'))) {
                    $this->addFieldError('time', $this->modx->lexicon('newsletter.newsletter_error_date'));
                } else {
                    $date = date('Y-m-d H:i:s', strtotime($this->getProperty('date') . ' ' . $this->getProperty('time')));

                    $resource->fromArray([
                        'cacheable' => 0
                    ]);

                    if ($resource->save()) {
                        $object = $this->modx->newObject('NewsletterNewsletterQueue', [
                            'type'          => 'send',
                            'newsletter_id' => $this->object->get('id'),
                            'emails'        => $this->getProperty('emails'),
                            'date'          => $date,
                            'days'          => $this->getProperty('days'),
                            'repeat'        => $this->getProperty('repeat')
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

                    $resource->save();
                }
            } else {
                $this->addFieldError('name', $this->modx->lexicon('newsletter.newsletter_error_resource_template'));
            }
        }

        return parent::beforeSave();
    }
}

return 'NewsletterNewsletterQueueSendProcessor';
