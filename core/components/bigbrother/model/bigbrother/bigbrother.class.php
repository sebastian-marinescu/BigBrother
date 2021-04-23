<?php

use Google\Auth\OAuth2;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

/**
 * Service class for Big Brother v2.
 *
 * @author Stephane Boulard <lossendae@gmail.com>
 * @author Mark Hamstra <mark@modmore.com>
 * @author Murray Wood <murray@digitalpenguin.hk>
 * @package bigbrother
 */
class BigBrother
{
    /**
     *  A reference to the modX object.
     *
     * @var modX
     */
    public $modx;

    /**
     * Configuration options loaded from system settings
     *
     * @var array
     */
    public $config = [];

    /**
     * Custom cache options to make sure data is cached to a custom cache partition instead of default.
     *
     * @var array
     */
    public $cacheOptions = [
        xPDO::OPT_CACHE_KEY => 'bigbrother',
    ];

    /**
     * The version string, used for cache busting and should be increased with each release.
     *
     * @var string
     */
    public $version = '2.0.0-dev01';

    /**
     * An instance of the Google Cloud PHP SDK's OAuth2 object. Used to pass into various Client as `credentials`.
     *
     * @see BigBrother::getOAuth2()
     * @var \Google\Auth\OAuth2
     */
    protected $OAuth2;

    /**
     * Constructor to load the config as needed.
     *
     * @param modX &$modx The modX object
     * @param array $config Optionally additional config properties that override
     * behaviour.
     */
    public function __construct(modX $modx, array $config = [])
    {
        $this->modx =& $modx;

        $core = $this->modx->getOption('bigbrother.core_path', $config, $this->modx->getOption('core_path') . 'components/bigbrother/');
        $assetsUrl = $this->modx->getOption('bigbrother.assets_url', $config, $this->modx->getOption('assets_url') . 'components/bigbrother/');

        $this->config = array_merge([
            'core_path' => $core,
            'model_path' => $core . 'model/',
            'processors_path' => $core . 'processors/',
            'controllers_path' => $core . 'controllers/',
            'templates_path' => $core . 'templates/',
            'chunks_path' => $core . 'elements/chunks/',
            'assets_url' => $assetsUrl,
            'connector_url' => $assetsUrl . 'connector.php',
        ], $config);

        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('bigbrother:default');
        }
    }

    /**
     * Returns an initialised instance of the Google SDK OAuth2 object.
     *
     * This will be filled with the client_id, client_secret, and necessary URIs for scopes and authorizations.
     * If a refresh token is available (system setting), that's also loaded.
     * If an access token is available (cache), that's also loaded. If not, but a refresh token is set, then it will
     * fetch a new access token automatically and save that.
     *
     * @return OAuth2
     */
    public function getOAuth2(): OAuth2
    {
        if (!$this->OAuth2) {
            $clientId = $this->modx->getOption('bigbrother.native_app_client_id');
            $clientSecret = $this->modx->getOption('bigbrother.native_app_client_secret');

            $this->OAuth2 = new OAuth2([
                'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
                'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
                'authorizationUri' => 'https://accounts.google.com/o/oauth2/auth',
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
            ]);
        }

        // If the scope doesn't already have the refresh token, but we do have it, set it
        if (!$this->OAuth2->getRefreshToken()) {
            $refreshToken = $this->modx->getOption('bigbrother.refresh_token');
            if (!empty($refreshToken)) {
                $this->OAuth2->setRefreshToken($refreshToken);
            }
        }

        // If the scope doesn't already have the access token...
        if (!$this->OAuth2->getAccessToken()) {
            // If we have it in cache and it's the right (array) format, set it
            $accessToken = $this->modx->cacheManager->get('ga4_access_token', $this->cacheOptions);
            if (is_array($accessToken)
                && array_key_exists('access_token', $accessToken)
                && !empty($accessToken['access_token'])
            ) {
                $this->OAuth2->updateToken($accessToken);
            } // If we don't have the access token, but we do have a refresh token, fetch a new auth token
            elseif ($this->OAuth2->getRefreshToken()) {
                $accessToken = $this->OAuth2->fetchAuthToken();
                // Turn expires_in into an absolute time to avoid reading from cache not determining it's still valid
                $accessToken['expires_at'] = time() + $accessToken['expires_in'];
                unset($accessToken['expires_in']);

                // Save it in the cache until 1 minute before its expiration time
                $this->modx->cacheManager->set('ga4_access_token', $accessToken, $accessToken['expires_in'] - 60,
                    $this->cacheOptions);
            }
        }

        return $this->OAuth2;
    }

    /**
     * Format metric value for front end display
     *
     * @access public
     * @param string $name The metric name
     * @param string $value The metric value
     * @return string The formatted metric value
     * @deprecated Miiiiight come in handy so keeping it for now - but consider this a prima target for refactor
     */
    public function formatValue($name, $value)
    {
        switch ($name) {
            case 'ga:avgTimeOnSite':
                $value = sprintf("%02u:%02u:%02u", $value / 3600, $value % 3600 / 60, $value % 60);
                break;
            case 'ga:percentNewVisits':
            case 'ga:visitBounceRate':
            case 'ga:exitRate':
                $value = round($value, 2) . ' %';
                break;
            case 'ga:pageviewsPerVisit':
                $value = round($value, 2);
                break;
            default:
                break;
        }

        return $value;
    }

    /**
     * Helper method to update/create a new MODx system setting
     *
     * @param string $key The setting key
     * @param mixed $value The setting value
     * @param string $type The setting type (Optionnal) default to textfield
     * @access public
     * @return boolean
     * @deprecated Miiiiiight come in handy, though (1) settings ought to always have been created in build
     * and (2) might be better in the one or two processors that handle it which then also clear the settings cache
     */
    public function updateOption($key, $value, $type = 'textfield')
    {
        $setting = $this->modx->getObject('modSystemSetting', array(
            'key' => 'bigbrother.' . $key,
        ));
        if (!$setting) {
            $setting = $this->modx->newObject('modSystemSetting');
        }
        $setting->fromArray(array(
            'key' => 'bigbrother.' . $key,
            'value' => $value,
            'xtype' => $type,
            'namespace' => 'bigbrother',
            'area' => 'Google Analytics for MODx Revolution',
        ), '', true);

        return $setting->save();
    }
}
