<?php


require_once dirname(__DIR__, 2) . '/model/bigbrother/bigbrother.class.php';


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

        $this->bigbrother->modx->lexicon->load('bigbrother:mgr');
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
            $authLink = $this->modx->getOption('manager_url') . '?namespace=bigbrother&a=authorize';
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
                <img src="{$this->modx->getOption('assets_url')}components/bigbrother/images/modmore.svg" alt="a modmore product">
            </a>
        </p>
    </div>
</div>
HTML;
        }
        return true;
    }
}
