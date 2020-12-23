<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

abstract class NewsletterManagerController extends modExtraManagerController
{
    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('newsletter', 'Newsletter', $this->modx->getOption('newsletter.core_path', null, $this->modx->getOption('core_path') . 'components/newsletter/') . 'model/newsletter/');

        $this->addCss($this->modx->newsletter->config['css_url'] . 'mgr/newsletter.css');

        $this->addJavascript($this->modx->newsletter->config['js_url'] . 'mgr/newsletter.js');

        $this->addJavascript($this->modx->newsletter->config['js_url'] . 'mgr/extras/extras.js');

        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                MODx.config.help_url = "' . $this->modx->newsletter->getHelpUrl() . '";
                
                Newsletter.config = ' . $this->modx->toJSON(array_merge($this->modx->newsletter->config, [
                    'branding_url'          => $this->modx->newsletter->getBrandingUrl(),
                    'branding_url_help'     => $this->modx->newsletter->getHelpUrl()
                ])) . ';
            });
        </script>');

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getLanguageTopics()
    {
        return $this->modx->newsletter->config['lexicons'];
    }

    /**
     * @access public.
     * @returns Boolean.
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('newsletter');
    }
}

class IndexManagerController extends NewsletterManagerController
{
    /**
     * @access public.
     * @return String.
     */
    public static function getDefaultController()
    {
        return 'home';
    }
}
