<?php
/**
 * BigBrother
 *
 *
 * @package bigbrother
 * @subpackage controllers
 */

require_once dirname(__DIR__) . '/model/bigbrother/bigbrother.class.php';

class BigbrotherAuthorizeManagerController extends modExtraManagerController {
    /** @var BigBrother $bigbrother */
    protected $bigbrother;
    /**
     * @var string
     */
    protected $assetsUrl;

    public function getPageTitle()
    {
        return $this->modx->lexicon('bigbrother');
    }

    public function getLanguageTopics()
    {
        return ['bigbrother:default'];
    }

    public function initialize()
    {
        $this->bigbrother = new BigBrother($this->modx);

        $this->assetsUrl = $this->bigbrother->config['assets_url'];
        $config = $this->modx->toJSON([
            'assetsUrl' => $this->assetsUrl,
            'connectorUrl' => $this->bigbrother->config['connector_url'],
            'version' => $this->bigbrother->version,
        ]);

        $this->addJavascript($this->assetsUrl . 'mgr/bigbrother.class.js?v=' . urlencode($this->bigbrother->version));
        $this->addJavascript($this->assetsUrl . 'mgr/authorize.panel.js?v=' . urlencode($this->bigbrother->version));
        $this->addCss($this->assetsUrl . 'css/mgr.css?v=' . urlencode($this->bigbrother->version));
        $this->addHtml(<<<HTML
<script>
    BigBrother.config = $config;
</script>
HTML
        );
        $this->addJavascript($this->assetsUrl . 'mgr/authorize.page.js?v=' . urlencode($this->bigbrother->version));
    }

    /**
     * @param array $scriptProperties
     * @throws \Google\ApiCore\ApiException
     * @throws \Google\ApiCore\ValidationException
     */
    public function process(array $scriptProperties = array())
    {

        $clientId = $this->modx->getOption('bigbrother.native_app_client_id');
        $clientSecret = $this->modx->getOption('bigbrother.native_app_client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            $this->failure($this->modx->lexicon('bigbrother.authorization.failure.missing_id_or_secret'));
            return;
        }

        try {
            $oauth = $this->bigbrother->getOAuth2();
        } catch (Exception $e) {
            $this->failure($this->modx->lexicon('bigbrother.guzzle_error'));
            return;
        }

        $authUrl = $oauth->buildFullAuthorizationUri();
        $this->addHtml("<script>BigBrother.config.authorizeUrl = '$authUrl';</script>");
    }

    public function getTemplateFile()
    {
        return $this->bigbrother->config['templates_path'] . 'page.tpl';
    }
}

