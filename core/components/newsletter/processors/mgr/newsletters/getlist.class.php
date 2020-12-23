<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewslettersNewsletterGetListProcessor extends modObjectGetListProcessor
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
    public $languageTopics = ['newsletter:default', 'base:newsletter'];

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortField = 'Newsletter.id';

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortDirection = 'DESC';

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
        $criteria->setClassAlias('Newsletter');

        $criteria->select($this->modx->getSelectColumns('NewsletterNewsletter', 'Newsletter'));
        $criteria->select($this->modx->getSelectColumns('modResource', 'modResource', 'resource_', ['id', 'pagetitle', 'published']));

        $criteria->innerJoin('modResource', 'modResource', [
            'modResource.id = Newsletter.resource_id']
        );
        $criteria->innerJoin('modContext', 'modContext', [
            'modResource.context_key = modContext.key'
        ]);

        $criteria->where([
            'modResource.context_key' => $this->getProperty('context')
        ]);

        $query = $this->getProperty('query');

        if (!empty($query)) {
            $criteria->where([
                'modResource.id:LIKE'           => '%' . $query . '%',
                'OR:modResource.pagetitle:LIKE' => '%' . $query . '%',
                'OR:modResource.longtitle:LIKE' => '%' . $query . '%'
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
            'name'              => $object->get('resource_pagetitle') . ($this->modx->hasPermission('tree_show_resource_ids') ? ' (' . $object->get('resource_id') . ')' : ''),
            'published'         => $object->get('resource_published'),
            'url'               => $this->modx->makeUrl($object->get('resource_id'), null, [
                'subscribe.name'    => $this->modx->getOption('sender_name', $this->modx->newsletter->config, 'test'),
                'subscribe.email'   => $this->modx->getOption('sender_email', $this->modx->newsletter->config, 'test@test.com')
            ], 'full'),
            'status'            => 0,
            'last_date'         => '',
            'last_date_format'  => '',
            'last_time_format'  => '',
            'history'           => []
        ]);

        foreach ((array) $object->getPrevQueue() as $queue) {
            $array['status']            = (int) $queue->get('status') === 0 ? 1 : 2;
            $array['last_date']         = date($this->getProperty('dateFormat'), strtotime($queue->get('date')));
            $array['last_date_format']  = date($this->modx->getOption('manager_date_format'), strtotime($queue->get('date')));
            $array['last_time_format']  = date($this->modx->getOption('manager_time_format'), strtotime($queue->get('date')));
        }

        foreach ((array) $object->getNextQueue() as $queue) {
            $array['status']            = 1;
            $array['last_date']         = date($this->getProperty('dateFormat'), strtotime($queue->get('date')));
            $array['last_date_format']  = date($this->modx->getOption('manager_date_format'), strtotime($queue->get('date')));
            $array['last_time_format']  = date($this->modx->getOption('manager_time_format'), strtotime($queue->get('date')));
        }

        foreach ((array) $object->getPrevQueue(10, null) as $queue) {
            if ((int) $queue->get('status') === 1) {
                $history = array_merge($queue->toArray(), [
                    'date'  => date($this->modx->getOption('manager_date_format', 'Y-m-d') . ', ' . $this->modx->getOption('manager_time_format', 'H:i'), strtotime($queue->get('date'))),
                    'lists' => [],
                    'log'   => []
                ]);

                if ($log = json_decode($queue->get('log'), true)) {
                    $history['log'] = $log;
                }

                foreach ((array) $queue->getLists() as $list) {
                    $name           = $list->get('name');
                    $translationKey = 'newsletter.list_' . $list->get('name');

                    if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                        $name = $translation;
                    }

                    $history['lists'][] = $name;
                }

                $array['history'][] = $history;
            }
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

return 'NewslettersNewsletterGetListProcessor';
