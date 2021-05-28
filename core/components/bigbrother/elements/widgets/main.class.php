<?php


require_once dirname(__DIR__, 2) . '/model/bigbrother/bigbrother.class.php';


class BigBrotherMainDashboardWidget extends modDashboardWidgetInterface
{
    public $cssBlockClass = 'bigbrother-widget bigbrother-widget--main';
    /**
     * @var BigBrother
     */
    protected $bigbrother;
    /**
     * @var mixed
     */
    protected $assetsUrl;

    public function render()
    {
        $this->initialize();

        // Make sure the authorization and property selection was completed. If not, show a message about needing to authorize first
        $property = $this->bigbrother->getPropertyID();
        $oauth = $this->bigbrother->getOAuth2();
        if (empty($property) || empty($oauth->getAccessToken())) {
            $authLink = $this->modx->getOption('manager_url') . '?namespace=bigbrother&a=authorize';
            return <<<HTML
<div class="bigbrother-inner-widget">
    <p class="bigbrother-warning">
        Big Brother has not yet been authorized, the authorization was revoked, or a Google Analytics property has not yet been selected. Once authorized and configured, this dashboard widget will show your Google Analytics statistics.
        <br><br>
        <a href="{$authLink}" class="x-btn">Authorize now &raquo;</a>
    </p>
</div>
<p class="bigbrother-credits bigbrother-credits--justified">
    <span class="bigbrother-credits__version">Powered by Big Brother v{$this->bigbrother->version}</span>
    <a href="https://www.modmore.com/extras/bigbrother/?utm_source=bigbrother_footer" target="_blank" rel="noopener" class="bigbrother-credits__logo">
        <img src="/BigBrother/assets/components/bigbrother/images/modmore.svg" alt="a modmore product">
    </a>
</p>
HTML;
        }


        // Adjust the name shown in the widget title bar - alternatively we could also extend process() instead of
        // render() for more control, but that may require more maintenance to keep cross-version compatible
        $this->widget->set('name', 'Google Analytics for &lt;Property Name&gt;');



        // Return the widget contents with a spinner which will client-side build the rest of the UI.
        return <<<HTML
<div class="bigbrother-inner-widget">
    <div class="bigbrother-spinner" id="bb{$this->widget->get('id')}-spinner"></div>
    <div class="bigbrother-row">
        <div id="bb{$this->widget->get('id')}-visits-line"  style="position:relative; width: 100%; height: 200px"></div>
    </div>
</div>


<script>
Ext.onReady(function() {
    let charts = [];
    charts.push(BigBrother.VisitsLineGraph(document.getElementById("bb{$this->widget->get('id')}-visits-line")));
    BigBrother.registerCharts(charts);
});
</script>
HTML;
    }

    protected function initialize(): void
    {
        $this->bigbrother = new BigBrother($this->modx);
        $this->assetsUrl = $this->bigbrother->config['assets_url'];
        $this->controller->addCss($this->assetsUrl . 'css/mgr.css?v=' . urlencode($this->bigbrother->version));

        $this->controller->addJavascript($this->assetsUrl . 'node_modules/chart.js/dist/chart.js?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'node_modules/luxon/build/global/luxon.min.js?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'node_modules/chartjs-adapter-luxon/dist/chartjs-adapter-luxon.min.js?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'mgr/bigbrother.class.js?v=' . urlencode($this->bigbrother->version));
        $this->controller->addJavascript($this->assetsUrl . 'mgr/reports/visits.js?v=' . urlencode($this->bigbrother->version));

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
}

return BigBrotherMainDashboardWidget::class;
