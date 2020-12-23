<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterListImportProcessor extends modObjectProcessor
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

        if ($this->getProperty('headers') === null) {
            $this->setProperty('headers', 0);
        }

        if ($this->getProperty('reset') === null) {
            $this->setProperty('reset', 0);
        }

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function process()
    {
        if (!is_dir($this->getProperty('directory'))) {
            if (!mkdir($this->getProperty('directory'), 0777, true)) {
                return $this->failure($this->modx->lexicon('newsletter.import_dir_failed'));
            }
        }

        if (!empty($_FILES['file'])) {
            $filename       = $_FILES['file']['name'];
            $newFilename    = substr($filename, 0, strrpos($filename, '.')) . '.' . time() . '.csv';
            $extension      = substr($filename, strrpos($filename, '.') + 1, strlen($filename));

            if (strtolower($extension) === 'csv') {
                $file = $this->getProperty('directory') . $newFilename;

                if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
                    if ($fopen = fopen($file, 'rb')) {
                        if (!empty($this->getProperty('reset'))) {
                            $this->modx->removeCollection('NewsletterListSubscription', [
                                'list_id' => $this->getProperty('id')
                            ]);
                        }

                        $current = 0;
                        $columns = ['email', 'name', 'active', 'data', 'type', 'context', 'token', 'edited', 'editedon'];

                        while ($row = fgetcsv($fopen, 1000, $this->getProperty('delimiter'))) {
                            if ($current === 0 && !empty($this->getProperty('headers'))) {
                                $columns = $row;
                            } else {
                                $data = [
                                    'email'     => '',
                                    'context'   => $this->modx->getOption('default_context'),
                                    'active'    => 1,
                                    'token'     => md5(time()),
                                    'edited'    => uniqid()
                                ];

                                foreach ($columns as $key => $value) {
                                    if (isset($row[$key])) {
                                        $data[$value] = trim($row[$key]);
                                    }
                                }

                                if (!empty($data['email'])) {
                                    $criterea = [
                                        'context'   => $data['context'],
                                        'email'     => $data['email']
                                    ];

                                    $subscription = $this->modx->getObject('NewsletterSubscription', $criterea);

                                    if (!$subscription) {
                                        $subscription = $this->modx->newObject('NewsletterSubscription', [
                                            'type' => 'import'
                                        ]);
                                    }

                                    $subscription->fromArray($data);

                                    $list = $this->modx->getObject('NewsletterListSubscription', [
                                        'subscription_id'   => $subscription->get('id'),
                                        'list_id'           => $this->getProperty('id')
                                    ]);

                                    if (!$list) {
                                        $list = $this->modx->newObject('NewsletterListSubscription');

                                        if ($list) {
                                            $list->fromArray([
                                                'list_id' => $this->getProperty('id')
                                            ]);

                                            $subscription->addMany($list);
                                        }
                                    }

                                    $subscription->save();
                                }
                            }

                            $current++;
                        }

                        return $this->success($this->modx->lexicon('failed'));
                    }

                    return $this->failure($this->modx->lexicon('newsletter.import_read_failed'));
                }

                return $this->failure($this->modx->lexicon('newsletter.import_upload_failed'));
            }

            return $this->failure($this->modx->lexicon('newsletter.import_valid_failed'));
        }

        return $this->failure($this->modx->lexicon('newsletter.import_valid_failed'));
    }
}

return 'NewsletterListImportProcessor';
