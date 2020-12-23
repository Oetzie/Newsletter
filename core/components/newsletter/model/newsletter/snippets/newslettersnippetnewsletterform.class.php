<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once dirname(__DIR__) . '/newslettersnippets.class.php';

class NewsletterSnippetNewsletterForm extends NewsletterSnippets
{
    /**
     * @access public.
     * @var Array.
     */
    public $properties = [
        'type'          => '',
        'list'          => [],
        'data'          => [],
        'confirm'       => false,
        'placeholder'   => 'newsletter'
    ];

    /**
     * @access public.
     * @param String $event.
     * @param Array $properties.
     * @param Object $form.
     * @return Boolean.
     */
    public function run($event, array $properties = [], $form)
    {
        if ($event === FormEvents::BEFORE_POST) {
            $this->setProperties($this->getFormattedProperties($properties));

            if ($this->getProperty('type') === 'subscribe') {
                if (isset($_GET['token'], $_GET['email'])) {
                    $form->getCollection()->setValue('email', $_GET['email']);

                    $subscription = $this->modx->getObject('NewsletterSubscription', [
                        'context'   => $this->modx->context->get('key'),
                        'email'     => $_GET['email'],
                        'token'     => $_GET['token']
                    ]);

                    if ($subscription) {
                        $subscription->fromArray([
                            'active'    => 1,
                            'edited'    => uniqid()
                        ]);

                        if ($subscription->save()) {
                            if ($email = $this->getProperty('email')) {
                                if (!$form->getEvents()->invokePlugin('email', $event, $email)) {
                                    return false;
                                }
                            }

                            if ($success = $this->getProperty('success')) {
                                $form->setProperty('success', $success);

                                $form->handleSuccess();
                            }

                            if ($tplSuccess = $this->getProperty('tplSuccess')) {
                                $form->setProperty('tplSuccess', $tplSuccess);
                            }

                            return true;
                        }
                    }

                    $form->getValidator()->setError('error_message', $this->modx->lexicon('newsletter.subscribe_confirm.error'));

                    if ($tplFailure = $this->getProperty('tplFailure')) {
                        $form->setProperty('tplFailure', $tplFailure);
                    }

                    return false;
                }
            }

            if ($this->getProperty('type') === 'unsubscribe') {
                if (isset($_GET['token'], $_GET['email'])) {
                    $form->getCollection()->setValue('email', $_GET['email']);

                    $subscription = $this->modx->getObject('NewsletterSubscription', [
                        'context'   => $this->modx->context->get('key'),
                        'email'     => $_GET['email'],
                        'token'     => $_GET['token']
                    ]);

                    if ($subscription) {
                        $subscription->fromArray([
                            'active'    => 2,
                            'edited'    => uniqid()
                        ]);

                        if ($subscription->save()) {
                            if ($email = $this->getProperty('email')) {
                                if (!$form->getEvents()->invokePlugin('email', $event, $email)) {
                                    return false;
                                }
                            }

                            if ($success = $this->getProperty('success')) {
                                $form->setProperty('success', $success);

                                $form->handleSuccess();
                            }

                            if ($tplSuccess = $this->getProperty('tplSuccess')) {
                                $form->setProperty('tplSuccess', $tplSuccess);
                            }

                            return true;
                        }
                    }

                    $form->getValidator()->setError('error_message', $this->modx->lexicon('newsletter.unsubscribe_confirm.error'));

                    if ($tplFailure = $this->getProperty('tplFailure')) {
                        $form->setProperty('tplFailure', $tplFailure);
                    }

                    return false;
                }
            }
        }

        if ($event === FormEvents::VALIDATE_SUCCESS) {
            $this->setProperties($this->getFormattedProperties($properties));

            if ($this->getProperty('type') === 'subscribe') {
                $subscription = $this->modx->getObject('NewsletterSubscription', [
                    'context'   => $this->modx->context->get('key'),
                    'email'     => $form->getCollection()->getValue('email')
                ]);

                if (!$subscription) {
                    $subscription = $this->modx->newObject('NewsletterSubscription', [
                        'context'   => $this->modx->context->get('key'),
                        'email'     => $form->getCollection()->getValue('email'),
                        'type'      => 'form',
                        'token'     => md5(time())
                    ]);
                }

                $subscription->fromArray([
                    'name'      => $form->getCollection()->getValue('name'),
                    'active'    => $this->getProperty('confirm') ? 0 : 1,
                    'edited'    => uniqid()
                ]);

                $data = [];

                foreach ((array) $this->getProperty('data') as $key) {
                    $value = $form->getCollection()->getValue($key);

                    if (is_array($value)) {
                        $data[$key] = implode(',', $value);
                    } else {
                        $data[$key] = $value;
                    }
                }

                $subscription->setData($data);

                $lists = $this->getProperty('list');

                foreach ($this->modx->getCollection('NewsletterList', ['primary' => 1]) as $list) {
                    $lists[] = $list->get('id');
                }

                foreach ((array) $lists as $id) {
                    $list = $this->modx->getObject('NewsletterListSubscription', [
                        'list_id'           => $id,
                        'subscription_id'   => $subscription->get('id')
                    ]);

                    if (!$list) {
                        $subscription->addMany($this->modx->newObject('NewsletterListSubscription', [
                            'list_id' => $id
                        ]));
                    }
                }

                if ($subscription->save()) {
                    if ($confirm = $this->getProperty('confirm')) {
                        if ($confirm['email']) {
                            $this->modx->toPlaceholders([
                                'subscribe_url' => $this->modx->makeUrl($this->modx->resource->get('id'), null, [
                                    'token'         => $subscription->get('token'),
                                    'email'         => $subscription->get('email')
                                ], 'full')
                            ], rtrim($this->getProperty('placeholder'), '.'));

                            if (!$form->getEvents()->invokePlugin('email', $event, $confirm['email'])) {
                                return false;
                            }
                        }

                        if (isset($confirm['success'])) {
                            $form->setProperty('success', $confirm['success']);
                        }
                    } else {
                        if ($email = $this->getProperty('email')) {
                            if (!$form->getEvents()->invokePlugin('email', $event, $email)) {
                                return false;
                            }
                        }

                        if ($success = $this->getProperty('success')) {
                            $form->setProperty('success', $success);
                        }

                        if ($tplSuccess = $this->getProperty('tplSuccess')) {
                            $form->setProperty('tplSuccess', $tplSuccess);
                        }
                    }

                    return true;
                }

                $form->getValidator()->setError('error_message', $this->modx->lexicon('newsletter.subscribe.error'));

                if ($tplFailure = $this->getProperty('tplFailure')) {
                    $form->setProperty('tplFailure', $tplFailure);
                }

                return false;
            }

            if ($this->getProperty('type') === 'unsubscribe') {
                $subscription = $this->modx->getObject('NewsletterSubscription', [
                    'context'   => $this->modx->context->get('key'),
                    'email'     => $form->getCollection()->getValue('email')
                ]);

                if ($subscription) {
                    $subscription->fromArray([
                        'active'    => 2,
                        'edited'    => uniqid()
                    ]);

                    if ($subscription->save()) {
                        if ($email = $this->getProperty('email')) {
                            if (!$form->getEvents()->invokePlugin('email', $event, $email)) {
                                return false;
                            }
                        }

                        if ($success = $this->getProperty('success')) {
                            $form->setProperty('success', $success);
                        }

                        if ($tplSuccess = $this->getProperty('tplSuccess')) {
                            $form->setProperty('tplSuccess', $tplSuccess);
                        }

                        return true;
                    }
                }

                $form->getValidator()->setError('error_message', $this->modx->lexicon('newsletter.unsubscribe.error'));

                if ($tplFailure = $this->getProperty('tplFailure')) {
                    $form->setProperty('tplFailure', $tplFailure);
                }

                return false;
            }
        }

        return true;
    }
}
