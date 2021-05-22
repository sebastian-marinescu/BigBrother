<?php
require_once dirname(__DIR__) . '/base.class.php';

class BigBrotherGetStateProcessor extends BigBrotherProcessor
{
    public function process()
    {
        $oAuth = $this->bigBrother->getOAuth2();
        $profileId = $this->bigBrother->getProfileId();
        $accounts = [];

        return $this->success('', [
            'has_refresh_token' => !empty($oAuth->getRefreshToken()),
            'has_access_token' => !empty($oAuth->getAccessToken()),
            'has_profile' => !empty($profileId),
            'profile_id' => $profileId,
            'accounts' => $accounts,
        ]);
    }
}

return BigBrotherGetStateProcessor::class;
