<?php

use Google\Analytics\Admin\V1alpha\AccountSummary;
use Google\Analytics\Admin\V1alpha\AnalyticsAdminServiceClient;
use Google\Analytics\Admin\V1alpha\PropertySummary;

require_once dirname(__DIR__) . '/base.class.php';

class BigBrotherGetStateProcessor extends BigBrotherProcessor
{
    public function process()
    {
        $oAuth = $this->bigBrother->getOAuth2();
        $propertyId = $this->bigBrother->getPropertyID();
        $property = $this->getGAProperty($oAuth, $propertyId);

        return $this->success('', [
            'has_refresh_token' => !empty($oAuth->getRefreshToken()),
            'has_access_token' => !empty($oAuth->getAccessToken()),
            'has_profile' => !empty($propertyId),
            'property' => $property,
            'accounts' => $this->getAccounts($oAuth),
        ]);
    }

    private function getAccounts(\Google\Auth\OAuth2 $oAuth)
    {
        if (empty($oAuth->getAccessToken())) {
            return [];
        }

        $cacheKey = 'bigbrother_accounts_' . sha1($oAuth->getRefreshToken());
        $cached = $this->modx->getCacheManager()->get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        $admin = new AnalyticsAdminServiceClient(['credentials' => $oAuth]);

        $accounts = [];

        /** @var AccountSummary[] $summaries */
        try {
            $summaries = $admin->listAccountSummaries([
                'pageSize' => 200,
            ]);
        } catch (\Google\ApiCore\ApiException $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[BigBrother] Received ' . get_class($e) . ' trying to list accounts and properties: ' . $e->getMessage());
            return [];
        }
        foreach ($summaries as $summary) {
            $account = [
                'account' => $summary->getAccount(),
                'displayName' => $summary->getDisplayName(),
                'total_properties' => 0,
                'properties' => [],
            ];

            /** @var PropertySummary[] $properties */
            $properties = $summary->getPropertySummaries();
            foreach ($properties as $property) {
                $propNum = substr($property->getProperty(), strlen('properties/'));

                $account['properties'][] = [
                    'propertyId' => $propNum,
                    'displayName' => $property->getDisplayName(),
                ];
                $account['total_properties']++;
            }

            usort($account['properties'], static function ($a, $b) {
                return $a['displayName'] > $b['displayName'] ? 1 : -1;
            });

            $accounts[] = $account;
        }

        usort($accounts, static function ($a, $b) {
            return strtolower($a['displayName']) > strtolower($b['displayName']) ? 1 : -1;
        });

        $this->modx->getCacheManager()->set($cacheKey, $accounts, 3600);
        return $accounts;
    }

    private function getGAProperty(\Google\Auth\OAuth2 $oAuth, string $propertyId)
    {
        if (empty($propertyId) || empty($oAuth->getAccessToken())) {
            return false;
        }

        $cacheKey = 'property_' . $propertyId . '_' . sha1($oAuth->getRefreshToken());
        $cached = $this->modx->getCacheManager()->get($cacheKey, BigBrother::$cacheOptions);
        if (is_array($cached)) {
            return $cached;
        }

        $admin = new AnalyticsAdminServiceClient(['credentials' => $oAuth]);

        try {
            // See if the property exists
            $property = $admin->getProperty('properties/' . $propertyId);
        } catch (\Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[BigBrother] Received ' . get_class($e) . ' verifying property: ' . $e->getMessage());
            return false;
        }

        $return = [
            'propertyId' => $propertyId,
            'displayName' => $property->getDisplayName(),
            'parent' => $property->getParent(),
        ];

        $this->modx->getCacheManager()->set($cacheKey, $return, 3000, BigBrother::$cacheOptions);
        return $return;
    }
}

return BigBrotherGetStateProcessor::class;
