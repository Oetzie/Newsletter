<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once __DIR__ .'/newsletter.class.php';

class NewsletterCronjob extends Newsletter
{
    /**
     * @access protected.
     * @var Boolean.
     */
    protected $debugMode = false;

    /**
     * @access protected.
     * @var Array.
     */
    protected $timer = [
        'start' => null,
        'end'   => null,
        'time'  => null
    ];

    /**
     * @access protected.
     * @var Array.
     */
    protected $logs = [
        'log'   => [],
        'html'  => [],
        'clean' => []
    ];

    /**
     * @access protected.
     * @var Integer.
     */
    protected $newsletterId = null;

    /**
     * @access public.
     * @param Boolean $debugMode.
     * @return Boolean.
     */
    public function setDebugMode($debugMode)
    {
        if ($debugMode) {
            $this->log('Debug mode is enabled. No database queries or mails will be executed.', 'notice');
        }

        $this->debugMode = $debugMode;

        return true;
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function getDebugMode()
    {
        return $this->debugMode;
    }


    /**
     * @access public.
     * @param Integer $newsletterId.
     * @return Boolean.
     */
    public function setNewsletterId($newsletterId)
    {
        $this->newsletterId = $newsletterId;

        return true;
    }

    /**
     * @access public.
     * @return Integer.
     */
    public function getNewsletterId()
    {
        return $this->newsletterId;
    }

    /**
     * @access protected.
     * @param String $type.
     */
    protected function setTimer($type)
    {
        $this->timer[$type] = microtime(true);

        switch ($type) {
            case 'start':
                $this->log('Start send process at ' . date('d-m-Y H:i:s') . '.');

                break;
            case 'end':
                $this->timer['time'] = $this->timer['end'] - $this->timer['start'];

                $this->log('End send process at ' . date('d-m-Y H:i:s') . '.');
                $this->log('Total execution time in seconds: ' . number_format($this->timer['time'], 2) . '.');

                break;
        }
    }

    /**
     * @access protected.
     * @param String $message.
     * @param String $level.
     */
    protected function log($message, $level = 'info')
    {
        switch ($level) {
            case 'error':
                $prefix = 'ERROR::';
                $color  = 'red';

                break;
            case 'notice':
                $prefix = 'NOTICE::';
                $color  = 'yellow';

                break;
            case 'success':
                $prefix = 'SUCCESS::';
                $color  = 'green';

                break;
            default:
                $prefix = 'INFO::';
                $color  = 'blue';

                break;
        }

        $log    = $this->colorize($prefix, $color) . ' ' . $message;
        $html   = '<span style="color: ' . $color . '">' . $prefix . '</span> ' . $message;

        if (XPDO_CLI_MODE) {
            $this->modx->log(MODX_LOG_LEVEL_INFO, $log);
        } else {
            $this->modx->log(MODX_LOG_LEVEL_INFO, $html);
        }

        /*
         * logMessage has CLI markup
         * htmlMessage has HTML markup
         * cleanMessage has no markup
         */
        $this->logs['log'][]   = $log;
        $this->logs['html'][]  = $html;
        $this->logs['clean'][] = $prefix . ' ' . $message;
    }

    /**
     * @access protected.
     * @param String $string.
     * @param String $color.
     * @return String.
     */
    protected function colorize($string, $color = 'white')
    {
        switch ($color) {
            case 'red':
                return "\033[31m" . $string . "\033[39m";

                break;
            case 'green':
                return "\033[32m" . $string . "\033[39m";

                break;
            case 'yellow':
                return "\033[33m" . $string . "\033[39m";

                break;
            case 'blue':
                return "\033[34m" . $string . "\033[39m";

                break;
            default:
                return $string;

                break;
        }
    }

    /**
     * @access protected.
     * @param String $type.
     * @return Boolean.
     */
    protected function setState($type)
    {
        switch ($type) {
            case 'start':
                // Noting to do

                break;
            case 'end':
                if ($log = $this->setLogFile()) {
                    if ((int) $this->modx->getOption('newsletter.log_send') === 1 && !$this->getDebugMode()) {
                        $this->sendLogFile($log);
                    }
                }

                $this->cleanFiles();

                break;
        }

        return true;
    }

    /**
     * @access protected.
     * @return String|Boolean.
     */
    protected function setLogFile()
    {
        $path       = dirname(dirname(__DIR__)) . '/logs/';
        $filename   = $path . date('Ymd_His') . '.log';

        if ($this->getDebugMode()) {
            $filename = $path . '_DEBUG_' . date('Ymd_His') . '.log';
        }

        if (is_dir($path) && is_writable($path)) {
            if ($handle = fopen($filename, 'wb')) {
                if (isset($this->logs['clean']) || count($this->logs['clean']) === 0) {
                    fwrite($handle, implode(PHP_EOL, $this->logs['clean']));
                    fclose($handle);

                    $this->log('Log file created `' . $filename . '`.', 'success');

                    return $filename;
                }

                $this->log('No messages to log', 'notice');
            } else {
                $this->log('Could not create log file.', 'notice');
            }
        } else {
            $this->log('Log directory `' . $path . '` does not exists or is not readable.', 'notice');
        }

        return false;
    }

    /**
     * @access protected.
     * @param String $log.
     * @return Boolean.
     */
    protected function sendLogFile($log)
    {
        $mail = $this->modx->getService('mail', 'mail.modPHPMailer');

        if ($mail) {
            $mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
            $mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
            $mail->set(modMail::MAIL_SUBJECT, $this->modx->getOption('site_name') . ' | Newsletter send');

            $mail->set(modMail::MAIL_BODY, $this->modx->getOption('newsletter.log_body', null, 'Log file is attached to this email.'));

            $mail->mailer->AddAttachment($log);

            $emails = explode(',', $this->modx->getOption('newsletter.log_email', null, $this->modx->getOption('emailsender')));

            foreach ($emails as $email) {
                $mail->address('to', trim($email));
            }

            if ($mail->send()) {
                $this->log('Log file send to `' . implode(', ', $emails) . '`.', 'success');
            } else {
                $this->log('Log file send failed.', 'error');
            }

            $mail->reset();
        }

        return true;
    }

    /**
     * @access protected.
     */
    protected function cleanFiles()
    {
        $this->log('Start clean up process.');

        $lifetime = $this->modx->getOption('newsletter.log_lifetime', null, 7);

        $this->log('Log file lifetime is `' . $lifetime . '` days.');

        $files = [
            'logs' => 0
        ];

        $path = dirname(dirname(__DIR__)).'/logs/';

        foreach (glob($path . '*.log') as $file) {
            if (filemtime($file) < (time() - (86400 * (int) $lifetime))) {
                unlink($file);

                $files['logs']++;
            }
        }

        $this->log($files['logs'] . ' log file(s) cleaned due lifetime.');

        $this->log('End clean up process.');
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function run()
    {
        $this->setState('start');
        $this->setTimer('start');

        $this->send();

        $this->setTimer('end');
        $this->setState('end');

        return true;
    }

    /**
     * @access public.
     * @return Boolean.
     */
    protected function send()
    {
        $mail = $this->modx->getService('mail', 'mail.modPHPMailer');

        if ($mail) {
            $criteria = $this->modx->newQuery('NewsletterNewsletterQueue', [
                'NewsletterNewsletterQueue.type'        => 'send',
                'NewsletterNewsletterQueue.date:>='     => date('Y-m-d H:i:s', strtotime('-30 minutes')),
                'NewsletterNewsletterQueue.date:<='     => date('Y-m-d H:i:s'),
                'NewsletterNewsletterQueue.status'      => 0,
                [
                    'NewsletterNewsletterQueue.repeat:>='       => 1,
                    'OR:NewsletterNewsletterQueue.repeat:='     => -1
                ]
            ]);

            $newsletterId = $this->getNewsletterId();

            if (empty($newsletterId)) {
                $this->log('Get all newsletters from queue.');
            } else {
                $this->log('Get newsletter #' . $newsletterId . ' form queue.');

                $criteria->where([
                    'NewsletterNewsletterQueue.newsletter_id' => $newsletterId
                ]);
            }

            $criteria->select($this->modx->getSelectColumns('NewsletterNewsletterQueue', 'NewsletterNewsletterQueue'));
            $criteria->select($this->modx->getSelectColumns('NewsletterNewsletter', 'NewsletterNewsletter', 'newsletter_', ['resource_id', 'filter']));

            $criteria->leftJoin('NewsletterNewsletter', 'NewsletterNewsletter', [
                '`NewsletterNewsletter`.`id` = `NewsletterNewsletterQueue`.`newsletter_id`'
            ]);

            $criteria->sortby('NewsletterNewsletterQueue.date', 'ASC');

            $queues = $this->modx->getCollection('NewsletterNewsletterQueue', $criteria);

            if (count($queues) === 1) {
                $this->log('1 newsletter in queue to send.');
            } else {
                $this->log(count($queues) . ' newsletters in queue to send.', 'info');
            }

            $current = 1;

            foreach ($queues as $queue) {
                $resource = $this->modx->getObject('modResource', [
                    'id'        => $queue->get('newsletter_resource_id'),
                    'published' => 1,
                    'deleted'   => 0
                ]);

                if ($resource) {
                    $lists  = [];
                    $emails = [
                        'success'   => [],
                        'failed'    => []
                    ];

                    foreach (array_filter(explode(',', $queue->get('emails'))) as $email) {
                        $email = trim($email);

                        $lists['email'][$email] = [
                            'email' => $email
                        ];
                    }

                    foreach ((array) $queue->getLists() as $list) {
                        foreach ((array) $list->getSubscriptions($resource->get('context_key')) as $subscription) {
                            $email = trim($subscription->get('email'));

                            $lists[$list->get('name')][$email] = array_merge([
                                'name'  => trim($subscription->get('name')),
                                'email' => $email,
                                'token' => $subscription->get('token')
                            ], $subscription->getData());
                        }
                    }

                    foreach ($lists as $list => $subscriptions) {
                        $translationKey = 'newsletter.list_' . $list;

                        if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                            $list = $translation;
                        }

                        if (!empty($queue->get('newsletter_filter'))) {
                            $filteredSubscriptions = $this->modx->runSnippet($queue->get('newsletter_filter'), [
                                'newsletter'    => $queue,
                                'subscriptions' => $subscriptions
                            ]);

                            if (is_array($filteredSubscriptions)) {
                                $subscriptions = $filteredSubscriptions;
                            }
                        }

                        if ($list === 'email') {
                            $this->log($current . ' of ' . count($queues) . ': Newsletter (#' . $queue->get('newsletter_id') . ') sending to individual email addresses.');
                        } else {
                            $this->log($current . ' of ' . count($queues) . ': Newsletter (#' . $queue->get('newsletter_id') . ') sending to list "' . $list . '".');
                        }

                        $count = 0;

                        foreach ($subscriptions as $subscription) {
                            if (!in_array($subscription['email'], $emails, true)) {
                                $placeholders = [];

                                foreach ((array) $subscription as $key => $value) {
                                    $placeholders['subscribe.' . $key] = $value;
                                }

                                $url = $this->modx->makeUrl($resource->get('id'), $resource->get('context_key'), [
                                    'newsletter' => str_rot13(serialize($placeholders))
                                ], 'full');

                                $mail->setHTML(true);

                                $mail->set(modMail::MAIL_FROM,      $this->config['sender_email']);
                                $mail->set(modMail::MAIL_FROM_NAME, $this->config['sender_name']);
                                $mail->set(modMail::MAIL_SUBJECT,   $this->getTitle($resource->get('pagetitle'), $placeholders));
                                $mail->set(modMail::MAIL_BODY,      $this->getContent($url, $placeholders));

                                $mail->address('to', $subscription['email']);

                                if (!$this->getDebugMode()) {
                                    if (!$mail->send()) {
                                        $this->log($current . ' of ' . count($queues) . ': Newsletter (#' . $queue->get('newsletter_id') . ') not sent to '.$subscription['email'] . ' because an email error.', 'error');

                                        $emails['failed'][] = $subscription['email'];
                                    } else {
                                        $this->log($current . ' of ' . count($queues) . ': Newsletter (#' . $queue->get('newsletter_id') . ') sent to ' . $subscription['email'] . '.');

                                        $emails['success'][] = $subscription['email'];
                                    }
                                } else {
                                    $this->log($current . ' of ' . count($queues) . ': Newsletter (#' . $queue->get('newsletter_id') . ') sent to ' . $subscription['email'] . '.');

                                    $emails['success'][] = $subscription['email'];
                                }

                                $mail->reset();
                            } else {
                                $this->log($current . ' of ' . count($queues) . ': Newsletter (#' . $queue->get('newsletter_id') . ') not sent to ' . $subscription['email'] . ' because an email duplicate.', 'notice');
                            }

                            $count++;
                        }
                    }

                    if (count($emails['success']) === 1) {
                        $this->log($current . ' of ' . count($queues) . ': Newsletter (#' . $queue->get('newsletter_id') . ') sent to 1 email.');
                    } else {
                        $this->log($current . ' of ' . count($queues) . ': Newsletter (#' . $queue->get('newsletter_id') . ') sent to ' . count($emails['success']) . ' emails.');
                    }

                    if (!$this->getDebugMode()) {
                        $queue->fromArray([
                            'status'    => 1,
                            'log'       => json_encode([
                                'success'   => $emails['success'],
                                'failed'    => $emails['failed']
                            ])
                        ]);

                        $queue->save();

                        if ($queue->get('repeat') !== -1) {
                            $repeat = $queue->get('repeat') - 1;

                            if ($repeat >= 1) {
                                $next   = date('Y-m-d', strtotime('+1 days'));
                                $days   = array_filter(explode(',', $queue->get('days')));
                                $names  = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

                                if (count($days) >= 1) {
                                    $filterDays = array_filter($days, function ($day) {
                                        return (int) $day > (int) date('N');
                                    });

                                    if (count($filterDays) >= 1) {
                                        $next = date('Y-m-d', strtotime('Next ' . $names[array_values($filterDays)[0] - 1]));
                                    } else {
                                        $next = date('Y-m-d', strtotime('Next ' . $names[array_values($days)[0] - 1]));
                                    }
                                }

                                $newQueue = $this->modx->newObject('NewsletterNewsletterQueue');

                                if ($newQueue) {
                                    $newQueue->fromArray([
                                        'newsletter_id' => $queue->get('newsletter_id'),
                                        'type'          => $queue->get('type'),
                                        'emails'        => $queue->get('emails'),
                                        'date'          => $next . ' ' . date('H:i:s', strtotime($queue->get('date'))),
                                        'days'          => $queue->get('days'),
                                        'repeat'        => $repeat
                                    ]);

                                    foreach ((array) $queue->getLists() as $list) {
                                        $newQueueList = $this->modx->newObject('NewsletterNewsletterQueueList', [
                                            'list_id' => $list->get('id')
                                        ]);

                                        if ($newQueueList) {
                                            $newQueue->addMany($newQueueList);
                                        }
                                    }

                                    $newQueue->save();
                                }
                            }
                        }
                    }
                } else {
                    $this->log($current . ' of ' . count($queues) . ': Newsletter (#' . $queue->get('newsletter_id') . ') could not be sent, the resource of newsletter does not exist or has been removed.', 'notice');
                }

                $current++;
            }

            $this->modx->invokeEvent('onNewsletterCronjob');
        } else {
            $this->log('Cannot initialize service mail service.');
        }

        return true;
    }
}
