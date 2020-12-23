<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterSubscriptionGetListProcessor extends modObjectGetListProcessor
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
    public $defaultSortField = 'Subscription.email';

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
            'dateFormat' => $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format')
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
        $criteria->setClassAlias('Subscription');

        $criteria->select($this->modx->getSelectColumns('NewsletterSubscription', 'Subscription'));
        $criteria->select($this->modx->getSelectColumns('modContext', 'modContext', 'context_', ['key', 'name']));

        $criteria->leftJoin('modContext', 'modContext', 'modContext.key = Subscription.context');

        $criteria->where([
            'Subscription.context:NOT IN'   => $this->modx->newsletter->config['exclude_contexts'],
            'Subscription.context'          => $this->getProperty('context')
        ]);

        $query = $this->getProperty('query');

        if (!empty($query)) {
            $criteria->where([
                'Subscription.name:LIKE'        => '%' . $query . '%',
                'OR:Subscription.email:LIKE'    => '%' . $query . '%'
            ]);
        }

        $status = $this->getProperty('status', '');

        if ($status !== '') {
            $criteria->where([
                'Subscription.active' => $status
            ]);
        }

        $list = $this->getProperty('list');

        if (!empty($list)) {
            $criteria->innerJoin('NewsletterListSubscription', 'List', [
                'List.subscription_id = Subscription.id'
            ]);

            $criteria->where([
                'List.list_id' => $list
            ]);
        }

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
            'lists'             => [],
            'lists_formatted'   => []
        ]);

        foreach ((array) $object->getLists() as $list) {
            $array['lists'][] = $list->get('id');

            $translationKey = 'newsletter.list_' . $list->get('name');

            if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                $array['lists_formatted'][] = $translation;
            } else {
                $array['lists_formatted'][] = $list->get('name');
            }
        }

        ksort($array['lists_formatted']);

        $array['lists_formatted'] = implode(', ', $array['lists_formatted']);

        if (in_array($object->get('editedon'), ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null], true)) {
            $array['editedon'] = '';
        } else {
            $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($object->get('editedon')));
        }

        return $array;
    }
}

return 'NewsletterSubscriptionGetListProcessor';
