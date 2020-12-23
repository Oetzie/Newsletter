<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once __DIR__ . '/newsletter.class.php';

class  NewsletterPlugins extends Newsletter
{
    /**
     * @access public.
     */
    public function onLoadWebDocument()
    {
        if (in_array($this->modx->resource->get('template'), $this->config['templates'], false)) {
            if (isset($_GET['newsletter'])) {
                foreach (unserialize(str_rot13($_GET['newsletter'])) as $key => $value) {
                    if (strpos($key, 'subscribe') !== false) {
                        $this->modx->setPlaceholder(str_replace('_', '.', $key), $value);
                    } else if (strpos($key, 'newsletter') !== false) {
                        $this->modx->setPlaceholder(str_replace('_', '.', $key), $value);
                    }
                }

                $this->modx->setPlaceholder('newsletter.url', $this->modx->makeUrl($this->modx->resource->get('id'), null, [
                    'newsletter' => $_GET['newsletter']
                ], 'full'));
            }
        }
    }
}
