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

    public function checkPermissions(): bool
    {
        return $this->modx->context->checkPolicy('bigbrother_authorize');
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
        $clientId = $this->modx->getOption('bigbrother.oauth_client_id');
        $clientSecret = $this->modx->getOption('bigbrother.oauth_client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            $this->failure($this->modx->lexicon('bigbrother.authorization.failure.missing_id_or_secret'));
            return;
        }

        if (!empty($scriptProperties['code'])) {
            $oAuth = $this->bigbrother->getOAuth2(true);
            $oAuth->setRefreshToken('');
            $oAuth->setAccessToken('');
            $oAuth->setCode($scriptProperties['code']);

            // Fetch new tokens
            try {
                $oAuth->setGrantType('authorization_code');
                $tokens = $oAuth->fetchAuthToken();
            } catch (Exception $e) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[BigBrother] Received oAuth error when verifying token: ' . $e->getMessage());
                $this->failure($this->modx->lexicon('bigbrother.oauth_error'));
                return $this->modx->lexicon('bigbrother.oauth_error');
            }

            if (array_key_exists('access_token', $tokens)) {
                $this->bigbrother->setAccessToken($tokens);
                $this->bigbrother->setOauthFlow('webapp');
                $this->modx->sendRedirect($this->getReturnUrl());
                return 'Code received...';
            }

            $this->modx->log(modX::LOG_LEVEL_ERROR, '[BigBrother] Received unexpected response from fetchAuthToken ' . print_r($tokens, true));
            $this->failure($this->modx->lexicon('bigbrother.authorization.failure.unexpected_response'));
            return $this->modx->lexicon('bigbrother.authorization.failure.unexpected_response');
        }


        $authUrl = "https://modmore.com/bigbrotherauth/?";
        $authUrl .= http_build_query([
            'client_id' => sha1($clientId),
            'return' => $this->getReturnUrl(),
        ]);
        $this->addHtml("<script>BigBrother.config.authorizeUrl = '$authUrl';</script>");
    }

    public function getTemplateFile()
    {
        return $this->bigbrother->config['templates_path'] . 'page.tpl';
    }

    private function getReturnUrl()
    {
        $url = 'https://'; // force https always
        $url .= $this->modx->getOption('http_host');
        $url .= $this->modx->getOption('manager_url');
        $url .= '?namespace=bigbrother&a=authorize';

        return $url;
    }
}

