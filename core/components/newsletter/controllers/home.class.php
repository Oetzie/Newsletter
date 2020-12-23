<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once dirname(__DIR__) . '/index.class.php';

class NewsletterHomeManagerController extends NewsletterManagerController
{
    /**
     * @access public.
     */
    public function loadCustomCssJs()
    {
        $this->addJavascript($this->modx->newsletter->config['js_url'] . 'mgr/widgets/home.panel.js');

        $this->addJavascript($this->modx->newsletter->config['js_url'] . 'mgr/widgets/newsletters.grid.js');
        $this->addJavascript($this->modx->newsletter->config['js_url'] . 'mgr/widgets/subscriptions.grid.js');
        $this->addJavascript($this->modx->newsletter->config['js_url'] . 'mgr/widgets/subscriptions.data.grid.js');
        $this->addJavascript($this->modx->newsletter->config['js_url'] . 'mgr/widgets/lists.grid.js');

        $this->addLastJavascript($this->modx->newsletter->config['js_url'] . 'mgr/sections/home.js');
    }

    /**
     * @access public.
     * @return String.
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('newsletter');
    }

    /**
    * @access public.
    * @return String.
    */
    public function getTemplateFile()
    {
        return $this->modx->newsletter->config['templates_path'] . 'home.tpl';
    }
}
