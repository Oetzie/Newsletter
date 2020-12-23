<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterFilterGetListProcessor extends modObjectProcessor
{
    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['newsletter:default', 'base:newsletter'];

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'newsletter.filter';

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
     * @return Array.
     */
    public function process()
    {
        $output = [];

        foreach ((array) $this->modx->newsletter->config['list_filters'] as $filter) {
            $filter     = trim($filter);
            $name       = $filter;
            $desc       = '';
            $nameKey    = 'newsletter.list_filter_' . strtolower($filter);
            $descKey    = 'newsletter.list_filter_' . strtolower($filter) . '_desc';

            if ($nameKey !== ($translation = $this->modx->lexicon($nameKey))) {
                $name = $translation;
            }

            if ($descKey !== ($translation = $this->modx->lexicon($descKey))) {
                $desc = $translation;
            }

            $output[$filter] = [
                'id'            => $filter,
                'name'          => $name,
                'description'   => $desc,
            ];
        }

        usort($output, function ($value1, $value2) {
            return $value1['name'] <=> $value2['name'];
        });

        return $this->outputArray($output);
    }
}

return 'NewsletterFilterGetListProcessor';
