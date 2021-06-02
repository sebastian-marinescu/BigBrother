<?php

require_once dirname(__DIR__, 2) . '/model/bigbrother/bigbrother.class.php';

use Google\Analytics\Admin\V1alpha\AnalyticsAdminServiceClient;
use Google\Analytics\Admin\V1alpha\Property;

abstract class BigBrotherAbstractDashboardWidget extends modDashboardWidgetInterface
{
    public static $initialized = false;

    /**
     * @var BigBrother
     */
    protected $bigbrother;
    /**
     * @var mixed
     */
    protected $assetsUrl;

    protected function initialize(): void
    {
        $this->bigbrother = new BigBrother($this->modx);

        if (static::$initialized) {
            return;
        }
        static::$initialized = true;

        $this->modx->lexicon->load('bigbrother:mgr');
        $this->assetsUrl = $this->bigbrother->config['assets_url'];
        $this->controller->addCss($this->assetsUrl . 'css/mgr.css?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'node_modules/chart.js/dist/chart.js?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'node_modules/luxon/build/global/luxon.min.js?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'node_modules/chartjs-adapter-luxon/dist/chartjs-adapter-luxon.min.js?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'mgr/bigbrother.class.js?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'mgr/reports/visits.js?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'mgr/reports/key-metrics.js?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'mgr/reports/acquisition.js?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'mgr/reports/popular-pages.js?v=' . urlencode($this->bigbrother->version));

        $config = $this->modx->toJSON([
            'assetsUrl' => $this->assetsUrl,
            'connectorUrl' => $this->bigbrother->config['connector_url'],
            'version' => $this->bigbrother->version,
        ]);
        $this->controller->addHtml(<<<HTML
<script>
    BigBrother.config = $config;
</script>
HTML
        );
    }

    /**
     * Checks if the authorization and property selection is complete. If it is, returns true. If it isn't returns a warning to render.
     *
     * @return bool|string
     */
    protected function isAuthorized()
    {
        $property = $this->bigbrother->getPropertyID();
        $oauth = $this->bigbrother->getOAuth2();
        if (empty($property) || empty($oauth->getAccessToken())) {
            $authLink = $this->bigbrother->getAuthorizeUrl();
            return <<<HTML
<div class="bigbrother-inner-widget">
    <div class="bigbrother-block">
        <p class="bigbrother-warning">
            {$this->modx->lexicon('bigbrother.not_authorized.warning')}
            <br><br>
            <a href="{$authLink}" class="x-btn">{$this->modx->lexicon('bigbrother.not_authorized.authorize_now')} &raquo;</a>
        </p>
        
        <p class="bigbrother-credits bigbrother-credits--justified">
            <span class="bigbrother-credits__version">{$this->modx->lexicon('bigbrother.powered_by_bigbrother')} v{$this->bigbrother->version}</span>
            <a href="https://www.modmore.com/extras/bigbrother/?utm_source=bigbrother_footer" target="_blank" rel="noopener" class="bigbrother-credits__logo">
                <img src="{$this->modx->getOption('bigbrother.assets_url')}images/modmore.svg" alt="a modmore product">
            </a>
        </p>
    </div>
</div>
HTML;
        }
        return true;
    }

    /**
     * @param int $propertyId
     * @return false|Property
     */
    protected function getGA4Property(int $propertyId)
    {
        $oAuth = $this->bigbrother->getOAuth2();

        try {
            $admin = new AnalyticsAdminServiceClient(['credentials' => $oAuth]);
            $property = $admin->getProperty('properties/' . $propertyId);
        } catch (\Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[BigBrother] Received ' . get_class($e) . ' verifying property: ' . $e->getMessage());
            return false;
        }

        return $property;
    }

    /**
     * @param int $propertyId
     * @return string
     */
    protected function getWidgetTitleBar(int $propertyId): string
    {
        $property = $this->getGA4Property($propertyId);

        return <<<HTML
<div class="bb-widget-title" style="width:100%; display:flex; justify-content: space-between">
    <div>
        <span>{$this->modx->lexicon('bigbrother.widget_title',['property_name' => $property->getDisplayName()])}</span>
        <span style="border-radius:3px; background-color:#fff; padding:6px 8px 3px; margin:-6px 0 -3px 6px;">{$propertyId}</span>
        <a href="{$this->bigbrother->getAuthorizeUrl()}" title="{$this->modx->lexicon('bigbrother.authorization')}" style="margin-left:8px; position:relative;"><i class="icon icon-cog" style="position:absolute; font-size:14px; top:-1px;"></i></a>
    </div>
    <div style="flex-grow:1; text-align:right;">
        <span id="bb-title-period" class="bb-title-period" style="color:#fff; padding:6px 8px 3px; margin:-6px -6px -3px 0; background-color:#00b5de; border-radius:3px;">{$this->modx->lexicon('bigbrother.loading')}</span>
    </div>
</div>
HTML;
    }
}
