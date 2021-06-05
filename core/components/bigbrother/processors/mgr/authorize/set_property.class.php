<?php

use Google\Analytics\Admin\V1alpha\AnalyticsAdminServiceClient;

require_once dirname(__DIR__) . '/base.class.php';

class BigBrotherSetPropertyProcessor extends BigBrotherProcessor
{
    public function process()
    {
        $this->modx->lexicon->load('bigbrother:default');
        $propertyId = (string)$this->getProperty('property');
        $propertyId = trim($propertyId);
        if (empty($propertyId)) {
            return $this->failure($this->modx->lexicon('bigbrother.error.select_a_property'));
        }

        $admin = new AnalyticsAdminServiceClient(['credentials' => $this->bigBrother->getOAuth2()]);

        try {
            // See if the property exists
            $property = $admin->getProperty('properties/' . $propertyId);
        } catch (\Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[BigBrother] Received ' . get_class($e) . ' verifying property: ' . $e->getMessage());
            return $this->failure($this->modx->lexicon('bigbrother.invalid_property'));
        }

        $this->bigBrother->setProperty($propertyId);
        $this->modx->getCacheManager()->delete('reports', BigBrother::$cacheOptions);

        return $this->success($this->modx->lexicon('bigbrother.save_property.success',['property_name' => $property->getDisplayName()]));
    }
}

return BigBrotherSetPropertyProcessor::class;
