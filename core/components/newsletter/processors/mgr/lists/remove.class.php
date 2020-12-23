<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterListRemoveProcessor extends modObjectRemoveProcessor
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
    public function beforeRemove()
    {
        if ($this->object->get('primairy') === 1) {
            $this->failure($this->modx->lexicon('newsletter.lists_remove_primary_list'));
        }

        return parent::beforeRemove();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function afterRemove()
    {
        if ($this->object->get('primairy') !== 1) {
            $this->modx->removeCollection('NewsletterListNewsletter', [
                'list_id' => $this->object->get('id')
            ]);

            $this->modx->removeCollection('NewsletterListSubscription', [
                'list_id' => $this->object->get('id')
            ]);
        }

        return parent::afterRemove();
    }
}

return 'NewsletterListRemoveProcessor';
