<?php
/**
 * BigBrother
 *
 *
 * @package bigbrother
 * @subpackage controllers
 */

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Auth\OAuth2;

require_once dirname(__DIR__) . '/model/bigbrother/bigbrother.class.php';

class BigbrotherAuthorizeManagerController extends modExtraManagerController {
    /** @var BigBrother $bigbrother */
    protected $bigbrother;
    /**
     * @var string
     */
    protected $assetsUrl;

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
        $this->addCss($this->assetsUrl . 'css/authorize.css?v=' . urlencode($this->bigbrother->version));
        $this->addHtml(<<<HTML
<script>
    BigBrother.config = $config;
</script>
HTML
        );
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
        $this->addHtml("<script>Ext.onReady(function() {
    MODx.load({
        xtype: 'bigbrother-panel-authorize',
        renderTo: 'bigbrother-page'
    });
});
</script>");

        return;

//            $authParams = array(
//                'scope' => 'https://www.googleapis.com/auth/analytics.readonly'
//            );
//            $url = $oAuthClient->getAuthenticationUrl($this->bigbrother->oauthEndpoint, 'urn:ietf:wg:oauth:2.0:oob', $authParams);

        return;
        $accessToken = $this->modx->cacheManager->get('ga4_access_token', $this->bigbrother->cacheOptions);


        return;
        $property = '268477819'; // @fixme
        echo '<pre>';


        $oauth = new OAuth2([
            'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
            'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
            'authorizationUri' => 'https://accounts.google.com/o/oauth2/auth',
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
        ]);
        $oauth->setCode($code);

        if (!empty($refreshToken)) {
            $oauth->setRefreshToken($refreshToken);
        }

        if (is_array($accessToken) && array_key_exists('access_token',
                $accessToken) && !empty($accessToken['access_token'])) {
            $oauth->updateToken($accessToken);
        } else {
            $accessToken = $oauth->fetchAuthToken();

            // Turn expires_in into an absolute time to avoid reading from cache not determining it's still valid
            $accessToken['expires_at'] = time() + $accessToken['expires_in'];
            unset($accessToken['expires_in']);
            $this->modx->cacheManager->set('ga4_access_token', $accessToken, $accessToken['expires_in'] - 60,
                $this->bigbrother->cacheOptions);
        }
    }

    public function getTemplateFile()
    {
        return $this->bigbrother->config['templates_path'] . 'page.tpl';
    }
}

