<?php


require_once dirname(__DIR__, 2) . '/model/bigbrother/bigbrother.class.php';
require_once __DIR__ . '/abstract.class.php';

use Google\Analytics\Admin\V1alpha\AnalyticsAdminServiceClient;

class BigBrotherMainDashboardWidget extends BigBrotherAbstractDashboardWidget
{
    public $cssBlockClass = 'bigbrother-widget bigbrother-widget--main';

    public function render()
    {
        // Load BigBrother etc
        $this->initialize();
        $this->modx->lexicon->load('bigbrother:mgr');

        // Make sure the authorization and property selection was completed. If not, show a message about needing to authorize first
        $authorized = $this->isAuthorized();
        if ($authorized !== true) {
            return $authorized;
        }

        // Get property name
        $propertyId = $this->bigbrother->getPropertyID();
        $oAuth = $this->bigbrother->getOAuth2();
        $admin = new AnalyticsAdminServiceClient(['credentials' => $oAuth]);
        try {
            $property = $admin->getProperty('properties/' . $propertyId);
        } catch (\Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[BigBrother] Received ' . get_class($e) . ' verifying property: ' . $e->getMessage());
            return false;
        }

        // Create authorization link
        $url = MODX_MANAGER_URL . '?namespace=bigbrother&a=authorize';

        // Create an HTML element for period dates in the title bar
        $titleElement = <<<HTML
<div class="bb-widget-title" style="width:100%; display:flex; justify-content: space-between">
    <div>
        <span>{$this->modx->lexicon('bigbrother.widget_title',['property_name' => $property->getDisplayName()])}</span>
        <span style="border-radius:3px; background-color:#fff; padding:6px 8px 3px; margin:-6px 0 -3px 6px;">{$propertyId}</span>
        <a href="{$url}" title="{$this->modx->lexicon('bigbrother.authorization')}" style="margin-left:8px; position:relative;"><i class="icon icon-cog" style="position:absolute; font-size:14px; top:-1px;"></i></a>
    </div>
    <div style="flex-grow:1; text-align:right;">
        <span id="bb-title-period" class="bb-title-period" style="color:#fff; padding:6px 8px 3px; margin:-6px -6px -3px 0; background-color:#00b5de; border-radius:3px;">{$this->modx->lexicon('bigbrother.loading')}</span>
    </div>
</div>
HTML;

        // Adjust the name shown in the widget title bar - alternatively we could also extend process() instead of
        // render() for more control, but that may require more maintenance to keep cross-version compatible
        $this->widget->set('name', $titleElement);

        // Register the initialisation of the charts within this widget
        $this->controller->addHtml(<<<HTML
<script>
Ext.onReady(function() {
    let charts = [];
    charts.push(BigBrother.VisitsLineGraph(document.getElementById("bb{$this->widget->get('id')}-visits-line")));
    charts.push(BigBrother.KeyMetrics(document.getElementById("bb{$this->widget->get('id')}-key-metrics")));
    charts.push(BigBrother.Acquisition(document.getElementById("bb{$this->widget->get('id')}-acquisition")));
    charts.push(BigBrother.PopularPages(document.getElementById("bb{$this->widget->get('id')}-popular-pages")));
    BigBrother.registerCharts(charts);
});
</script>
HTML
        );

        // Return the widget contents that will be enhanced by the scripts
        return <<<HTML
<div class="bigbrother-inner-widget">
    <div class="bigbrother-spinner" id="bb{$this->widget->get('id')}-spinner"></div>
    <div class="bigbrother-block">
        <div id="bb{$this->widget->get('id')}-visits-line" style="position:relative; width: 100%; height: 200px"></div>
    </div>
    <div class="bigbrother-block" style="min-height: 145px">
        <div id="bb{$this->widget->get('id')}-key-metrics"></div>
    </div>
    <div class="bigbrother-row">
        <div class="bigbrother-col bigbrother-block">
            <h3 class="bigbrother-block-title">{$this->modx->lexicon('bigbrother.acquisition_sources')}</h3>
            <div id="bb{$this->widget->get('id')}-acquisition" style="position:relative; height: 250px"></div>
        </div>
        <div class="bigbrother-col bigbrother-block">
            <h3 class="bigbrother-block-title">{$this->modx->lexicon('bigbrother.most_viewed_pages')}</h3>
            <div id="bb{$this->widget->get('id')}-popular-pages" class="bigbrother-report-list" style="position:relative; height: 250px"></div>
        </div>
    </div>
</div>
HTML;
    }
}

return BigBrotherMainDashboardWidget::class;
