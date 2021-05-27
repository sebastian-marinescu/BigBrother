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
        return ['bigbrother:default', 'bigbrother:mgr'];
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
            $this->failure('Missing client ID or Secret. Typically, Big Brother will provide you a default pair of Google Cloud credentials to ease the setup. However it appears those credentials are missing from your installation. These credentials need to be added before you can continue. <br><br><a href="https://docs.modmore.com/en/Open_Source/BigBrother/Custom_oAuth_Credentials.html" target="_blank" rel="noopener" style="font-weight: bold;">Learn more in the Big Brother documentation &raquo;</a>');

            return;
        }

        $oauth = $this->bigbrother->getOAuth2();

        $authUrl = $oauth->buildFullAuthorizationUri();
        $this->addHtml("<script>BigBrother.config.authorizeUrl = '$authUrl';</script>");
    }

    public function getTemplateFile()
    {
        return $this->bigbrother->config['templates_path'] . 'page.tpl';
    }
}

