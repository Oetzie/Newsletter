<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class NewsletterNewsletterSendProcessor extends modObjectUpdateProcessor
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
    public $objectType = 'newsletter.newsletter';

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
    public function beforeSave()
    {
        $type = $this->getProperty('type');
        $mail = $this->modx->getService('mail', 'mail.modPHPMailer');

        if ($mail) {
            $resource = $this->modx->getObject('modResource', [
                'id'        => $this->object->get('resource_id'),
                'published' => 1,
                'deleted'   => 0
            ]);

            if ($resource) {
                foreach ((array) $this->object->getQueue($type) as $queue) {
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

                        if (!empty($this->object->get('filter'))) {
                            $filteredSubscriptions = $this->modx->runSnippet($this->object->get('filter'), [
                                'newsletter'    => $this->object,
                                'subscriptions' => $subscriptions
                            ]);

                            if (is_array($filteredSubscriptions)) {
                                $subscriptions = $filteredSubscriptions;
                            }
                        }

                        if ($list === 'email') {
                            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('newsletter.newsletter_send_to_emails'));
                        } else {
                            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('newsletter.newsletter_send_to_list', [
                                'name' => $list
                            ]));
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

                                $mail->set(modMail::MAIL_FROM,      $this->modx->newsletter->config['sender_email']);
                                $mail->set(modMail::MAIL_FROM_NAME, $this->modx->newsletter->config['sender_name']);
                                $mail->set(modMail::MAIL_SUBJECT,   $this->modx->newsletter->getTitle($resource->get('pagetitle'), $placeholders));
                                $mail->set(modMail::MAIL_BODY,      $this->modx->newsletter->getContent($url, $placeholders));

                                $mail->address('to', $subscription['email']);

                                if (!$mail->send()) {
                                    $this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('newsletter.newsletter_send_email_error', [
                                        'current'   => $count + 1,
                                        'total'     => count($subscriptions),
                                        'email'     => $subscription['email']
                                    ]));

                                    $emails['failed'][] = $subscription['email'];
                                } else {
                                    $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('newsletter.newsletter_send_email_success', [
                                        'current'   => $count + 1,
                                        'total'     => count($subscriptions),
                                        'email'     => $subscription['email']
                                    ]));

                                    $emails['success'][] = $subscription['email'];
                                }

                                $mail->reset();
                            } else {
                                $this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('newsletter.newsletter_send_email_duplicate', [
                                    'current'   => $count + 1,
                                    'total'     => count($subscriptions),
                                    'email'     => $subscription['email']
                                ]));
                            }

                            $count++;
                        }
                    }

                    $queue->fromArray([
                        'status'    => 1,
                        'log'       => json_encode([
                            'success'   => $emails['success'],
                            'failed'    => $emails['failed']
                        ])
                    ]);

                    if ($type === 'test') {
                        $queue->set('date', date('Y-m-d H:i:s'));
                    }

                    $queue->save();

                    $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('newsletter.newsletter_send_feedback', [
                        'pagetitle' => $resource->get('pagetitle'),
                        'total'     => count($emails['success'])
                    ]));

                    sleep(1);
                }

                $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');

                $this->modx->cacheManager->refresh([
                    'registry' => [
                        $this->getProperty('register') => [
                            trim($this->getProperty('topic'), '/')
                        ]
                    ]
                ]);
            } else {
                $this->failure($this->modx->lexicon('newsletter.newsletter_error_resource_id'));
            }
        } else {
            $this->failure($this->modx->lexicon('newsletter.newsletter_send_error_desc'));
        }

        return parent::beforeSave();
    }
}

return 'NewsletterNewsletterSendProcessor';
