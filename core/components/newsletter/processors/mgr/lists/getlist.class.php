<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterListGetListProcessor extends modObjectGetListProcessor
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
    public $languageTopics = ['newsletter:default', 'base:newsletter'];

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortField = 'List.id';

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortDirection = 'ASC';

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

        $this->setDefaultProperties([
            'dateFormat'    => $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format'),
            'combo'         => false
        ]);

        return parent::initialize();
    }

    /**
     * @access public.
     * @param xPDOQuery $criteria.
     * @return xPDOQuery.
     */
    public function prepareQueryBeforeCount(xPDOQuery $criteria)
    {
        $criteria->setClassAlias('List');

        $criteria->select($this->modx->getSelectColumns('NewsletterList', 'List'));
        $criteria->select('COUNT(Subscription.id) as subscriptions');

        $criteria->leftJoin('NewsletterListSubscription', 'ListSubscription', [
            'ListSubscription.list_id = List.id'
        ]);

        $criteria->leftJoin('NewsletterSubscription', 'Subscription', [
            'ListSubscription.subscription_id = Subscription.id',
            'Subscription.context:NOT IN'   => $this->modx->newsletter->config['exclude_contexts'],
            'Subscription.context'          => $this->getProperty('context')
        ]);

        $query = $this->getProperty('query');

        if (!empty($query)) {
            $criteria->where([
                'List.name:LIKE' => '%' . $query . '%'
            ]);
        }

        $criteria->groupby('List.id');

        return $criteria;
    }

    /**
     * @access public.
     * @param xPDOObject $object.
     * @return Array.
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = array_merge($object->toArray(), [
            'name_formatted'        => $object->get('name'),
            'description_formatted' => $object->get('description')
        ]);

        $translationKey = 'newsletter.list_' . $object->get('name');

        if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
            $array['name_formatted'] = $translation;
        }

        $translationKey = 'newsletter.list_' . $object->get('name') . '_desc';

        if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
            $array['description_formatted'] = $translation;
        }

        if (in_array($object->get('editedon'), ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null], true)) {
            $array['editedon'] = '';
        } else {
            $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($object->get('editedon')));
        }

        if (!$this->getProperty('combo') && $object->get('hidden') === 1) {
            if ($this->modx->hasPermission('newsletter_admin')) {
                return $array;
            }
        } else {
            return $array;
        }
    }
}

return 'NewsletterListGetListProcessor';
