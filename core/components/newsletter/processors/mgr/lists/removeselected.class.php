<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterListRemoveSelectedProcessor extends modProcessor
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
                if ($object->get('primairy') !== 1) {
                    if ($object->remove()) {
                        $this->modx->removeCollection('NewsletterListNewsletter', [
                            'list_id' => $object->get('id')
                        ]);

                        $this->modx->removeCollection('NewsletterListSubscription', [
                            'list_id' => $object->get('id')
                        ]);
                    }
                }
            }
        }

        return $this->outputArray([]);
    }
}

return 'NewsletterListRemoveSelectedProcessor';
