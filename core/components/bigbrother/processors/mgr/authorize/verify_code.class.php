<?php
require_once dirname(__DIR__) . '/base.class.php';

class BigBrotherVerifyCodeProcessor extends BigBrotherProcessor
{
    public function process()
    {
        $code = (string)$this->getProperty('code');
        $code = trim($code);
        if (empty($code)) {
            return $this->failure($this->modx->lexicon('bigbrother.error.enter_auth_code'));
        }

        // Attempt validate the token by unsetting the refresh token and setting the code
        $oAuth = $this->bigBrother->getOAuth2();
        $oAuth->setRefreshToken('');
        $oAuth->setCode($code);

        // Fetch new tokens
        try {
            $tokens = $oAuth->fetchAuthToken();
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[BigBrother] Received oAuth error when verifying token: ' . $e->getMessage());
            return $this->failure($this->modx->lexicon('bigbrother.oauth_error'));
        }

        if (array_key_exists('access_token', $tokens)) {
            $this->bigBrother->setAccessToken($tokens);
            return $this->success($this->modx->lexicon('bigbrother.authorization.success'));
        }

        $this->modx->log(modX::LOG_LEVEL_ERROR, '[BigBrother] Received unexpected response from fetchAuthToken ' . print_r($tokens, true));
        return $this->failure($this->modx->lexicon('bigbrother.authorization.failure.unexpected_response'));
    }
}

return BigBrotherVerifyCodeProcessor::class;
