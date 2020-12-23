<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterListExportProcessor extends modObjectProcessor
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
    public $languageTopics = ['newsletter:default'];

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortField = 'email';

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
            'filename'  => $this->objectType . '.csv',
            'directory' => $this->modx->getOption('core_path') . 'cache/export/newsletter/',
            'delimiter' => ';'
        ]);

        if ($this->getProperty('download') === null) {
            $this->setProperty('download', 0);
        }

        if ($this->getProperty('headers') === null) {
            $this->setProperty('headers', 0);
        }

        return parent::initialize();
    }

    /**
     * @access public.
     * @param Array $columns.
     * @return Array.
     */
    public function getData(array $columns = [])
    {
        $data = [];

        $criteria = $this->modx->newQuery('NewsletterSubscription');

        $criteria->select($this->modx->getSelectColumns('NewsletterSubscription', 'NewsletterSubscription'));

        $criteria->leftJoin('NewsletterListSubscription', 'NewsletterListSubscription', [
            '`NewsletterListSubscription`.`subscription_id` = `NewsletterSubscription`.`id`'
        ]);

        $criteria->where([
            'NewsletterListSubscription.list_id' => $this->getProperty('id')
        ]);

        $criteria->sortby($this->defaultSortField, $this->defaultSortDirection);

        foreach ($this->modx->getCollection('NewsletterSubscription', $criteria) as $object) {
            $data[] = $object->toArray();
        }

        if (!empty($this->getProperty('headers'))) {
            array_unshift($data, $columns);
        }

        return $data;
    }

    /**
     * @access public.
     * @return mixed.
     */
    public function process()
    {
        if (!is_dir($this->getProperty('directory'))) {
            if (!mkdir($this->getProperty('directory'), 0777, true)) {
                return $this->failure($this->modx->lexicon('newsletter.lists_export_dir_failed'));
            }
        }

        $file = $this->getProperty('download');

        if (empty($file)) {
            return $this->setFile();
        }

        return $this->getFile();
    }

    /**
     * @access public.
     * @return mixed.
     */
    public function setFile()
    {
        $file = $this->getProperty('directory') . $this->getProperty('filename');

        if ($fopen = fopen($file, 'wb')) {
            $columns = ['email', 'name', 'active', 'data', 'type', 'context', 'token', 'edited', 'editedon'];

            foreach ($this->getData($columns) as $row => $value) {
                if (0 === $row) {
                    fputcsv($fopen, $value, $this->getProperty('delimiter'));
                } else {
                    $data = [];

                    foreach ($columns as $key => $column) {
                        if ($column === 'editedon') {
                            if (isset($value[$column])) {
                                if (in_array($value[$column], ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null], true)) {
                                    $data[$key] = '0000-00-00 00:00:00';
                                } else {
                                    $data[$key] = $value[$column];
                                }
                            } else {
                                $data[$key] = '0000-00-00 00:00:00';
                            }
                        } else {
                            if (isset($value[$column])) {
                                $data[$key] = $value[$column];
                            } else {
                                $data[$key] = '';
                            }
                        }
                    }

                    fputcsv($fopen, $data, $this->getProperty('delimiter'));
                }
            }

            fclose($fopen);

            return $this->success($this->modx->lexicon('success'));
        }

        return $this->failure($this->modx->lexicon('newsletter.lists_export_failed'));
    }

    /**
     * @access public.
     * @return mixed.
     */
    public function getFile()
    {
        $file = $this->getProperty('directory') . $this->getProperty('filename');

        if (is_file($file)) {
            $content = file_get_contents($file);

            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename="' . $this->getProperty('filename') . '"');

            return $content;
        }

        return $this->failure($this->modx->lexicon('newsletter.lists_export_failed'));
    }
}

return 'NewsletterListExportProcessor';
