<?php

/**
 * Newsletter
 *
 * Copyright 2020 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class Newsletter
{
    /**
     * @access public.
     * @var modX.
     */
    public $modx;

    /**
     * @access public.
     * @var Array.
     */
    public $config = [];

    /**
     * @access public.
     * @param modX $modx.
     * @param Array $config.
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath   = $this->modx->getOption('newsletter.core_path', $config, $this->modx->getOption('core_path') . 'components/newsletter/');
        $assetsUrl  = $this->modx->getOption('newsletter.assets_url', $config, $this->modx->getOption('assets_url') . 'components/newsletter/');
        $assetsPath = $this->modx->getOption('newsletter.assets_path', $config, $this->modx->getOption('assets_path') . 'components/newsletter/');

        $this->config = array_merge([
            'namespace'         => 'newsletter',
            'lexicons'          => ['newsletter:default', 'newsletter:web', 'base:newsletter', 'site:newsletter'],
            'base_path'         => $corePath,
            'core_path'         => $corePath,
            'model_path'        => $corePath . 'model/',
            'processors_path'   => $corePath . 'processors/',
            'elements_path'     => $corePath . 'elements/',
            'chunks_path'       => $corePath . 'elements/chunks/',
            'plugins_path'      => $corePath . 'elements/plugins/',
            'snippets_path'     => $corePath . 'elements/snippets/',
            'templates_path'    => $corePath . 'templates/',
            'assets_path'       => $assetsPath,
            'js_url'            => $assetsUrl . 'js/',
            'css_url'           => $assetsUrl . 'css/',
            'assets_url'        => $assetsUrl,
            'connector_url'     => $assetsUrl . 'connector.php',
            'version'           => '2.0.0',
            'branding_url'      => $this->modx->getOption('newsletter.branding_url', null, ''),
            'branding_help_url' => $this->modx->getOption('newsletter.branding_url_help', null, ''),
            'permissions'       => [
                'admin'             => (bool) $this->modx->hasPermission('newsletter_admin')
            ],
            'context'           => (bool) $this->getContexts(),
            'exclude_contexts'  => array_merge(['mgr'], explode(',', $this->modx->getOption('newsletter.exclude_contexts', null, ''))),
            'templates'         => explode(',', $this->modx->getOption('newsletter.templates', null, '')),
            'list_filters'      => explode(',', $this->modx->getOption('newsletter.list_filters', null, '')),
            'data_filter'       => $this->modx->getOption('newsletter.data_filter', null, ''),
            'sender_name'       => $this->modx->getOption('newsletter.name', null, $this->modx->getOption('site_name')),
            'sender_email'      => $this->modx->getOption('newsletter.email', null, $this->modx->getOption('emailsender')),
            'token'             => $this->modx->getOption('newsletter.token', null, md5(time()))
        ], $config);

        $this->modx->addPackage('newsletter', $this->config['model_path']);

        if (is_array($this->config['lexicons'])) {
            foreach ($this->config['lexicons'] as $lexicon) {
                $this->modx->lexicon->load($lexicon);
            }
        } else {
            $this->modx->lexicon->load($this->config['lexicons']);
        }
    }
    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getHelpUrl()
    {
        if (!empty($this->config['branding_help_url'])) {
            return $this->config['branding_help_url'] . '?v=' . $this->config['version'];
        }

        return false;
    }

    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getBrandingUrl()
    {
        if (!empty($this->config['branding_url'])) {
            return $this->config['branding_url'];
        }

        return false;
    }

    /**
     * @access public.
     * @param String $key.
     * @param Array $options.
     * @param Mixed $default.
     * @return Mixed.
     */
    public function getOption($key, array $options = [], $default = null)
    {
        if (isset($options[$key])) {
            return $options[$key];
        }

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $this->modx->getOption($this->config['namespace'] . '.' . $key, $options, $default);
    }

    /**
     * @access private.
     * @return Boolean.
     */
    private function getContexts()
    {
        return $this->modx->getCount('modContext', [
            'key:NOT IN' => array_merge(['mgr'], explode(',', $this->modx->getOption('newsletter.exclude_contexts', null, '')))
        ]) === 1;
    }

    /**
     * @access public.
     * @param String $title.
     * @param Array $placeholders.
     * @return Mixed.
     */
    public function getTitle($title, array $placeholders = [])
    {
        $chunk = $this->modx->newObject('modChunk');

        if ($chunk)  {
            $chunk->fromArray([
                'name' => sprintf('newsletter-title-%s', uniqid())
            ]);

            $chunk->setCacheable(false);

            return $chunk->process($placeholders, $title);
        }

        return '';
    }

    /**
     * @access public.
     * @param String $url.
     * @param Array $placeholders.
     * @return Mixed.
     */
    public function getContent($url, array $placeholders = [])
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL             => $url,
            CURLOPT_HEADER          => false,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_TIMEOUT         => 10
        ]);

        $response       = curl_exec($curl);
        $responseInfo   = curl_getinfo($curl);

        if (!isset($responseInfo['http_code']) || (int) $responseInfo['http_code'] !== 200) {
            return false;
        }

        curl_close($curl);

        return $response;
    }
}
