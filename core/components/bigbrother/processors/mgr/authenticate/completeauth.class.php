<?php
class BigBrotherCompleteAuthProcessor extends modProcessor
{
    /** @var BigBrother */
    public $bigbrother;

    public function initialize()
    {
        $this->bigbrother = $this->modx->bigbrother;
        return parent::initialize();
    }

    public function process()
    {
        $code = $this->getProperty('code');

        if (!empty($code)) {
            $client = $this->bigbrother->loadOAuth();
            // https://developers.google.com/identity/protocols/OAuth2InstalledApp
            $authParams = array(
                'code' => $code,
                'redirect_uri' => 'urn:ietf:wg:oauth:2.0:oob',
                'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
                'grant_type' => 'authorization_code'
            );

            $result = false;
            try {
                $result = $client->getAccessToken($this->bigbrother->oauthTokenEndpoint, 'authorization_code', $authParams);
            } catch (Exception $e) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Exception during getAccessToken: ' . $e->getMessage());
            }

            if (is_array($result) && $result['code'] == 200) {
                $accessToken = $result['result']['access_token'];
                $refreshToken = $result['result']['refresh_token'];
                $expiresIn = $result['result']['expires_in'];

                $this->modx->getCacheManager()->set('access_token', $accessToken, $expiresIn, $this->bigbrother->cacheOptions);
                $this->bigbrother->updateOption('refresh_token', $refreshToken, 'text-password');
                return $this->success('', array(
                    'text' => $this->modx->lexicon('bigbrother.authorize_success')
                ));
            }

            return $this->failure('Unable to complete oAuth2 flow: <pre>' . print_r($result, true) .'</pre>');
        }
        return $this->failure('No code provided.');
    }
}

return 'BigBrotherCompleteAuthProcessor';