<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterSubscriptionDataGetListProcessor extends modProcessor
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
    public $languageTopics = ['newsletter:default', 'base:newsletter'];

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortField = 'key';

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortDirection = 'ASC';

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
            'start' => 0,
            'limit' => 20,
            'sort'  => $this->defaultSortField,
            'dir'   => $this->defaultSortDirection,
            'query' => ''
        ]);

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
            $output = [];
            $data   = $object->getData();
            $query  = $this->getProperty('query');

            if ($this->getProperty('dir') === 'ASC') {
                if ($this->getProperty('sort') === 'key') {
                    ksort($data);
                } else {
                    asort($data);
                }
            } else {
                if ($this->getProperty('sort') === 'key') {
                    krsort($data);
                } else {
                    arsort($data);
                }
            }

            foreach ((array) $data as $key => $value) {
                if (!empty($query)) {
                    if (!preg_match('/' . $query . '/i', $key) && !preg_match('/' . $query . '/i', $value)) {
                        continue;
                    }
                }

                $value = [
                    'key'               => $key,
                    'key_formatted'     => $key,
                    'content'           => $value,
                    'content_formatted' => $value,
                    'description'       => '',
                    'subscription'      => $this->getProperty('id')
                ];

                $translationKey = 'newsletter.data_' . $key;

                if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                    $value['key_formatted'] = $translation;
                }

                $translationKey = 'newsletter.data_' . $key . '_desc';

                if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                    $value['description'] = $translation;
                }

                $output[] = $value;
            }

            if (!empty($this->modx->newsletter->config['data_filter'])) {
                $filteredOutput = $this->modx->runSnippet($this->modx->newsletter->config['data_filter'], [
                    'data'          => $output,
                    'subscription'  => $object
                ]);

                if (is_array($filteredOutput)) {
                    $output = $filteredOutput;
                }
            }

            return $this->outputArray(array_slice($output, $this->getProperty('start'), $this->getProperty('limit')), count($output));
        }

        return $this->failure($this->modx->lexicon('newsletter.subscription_data'));
    }
}

return 'NewsletterSubscriptionDataGetListProcessor';
