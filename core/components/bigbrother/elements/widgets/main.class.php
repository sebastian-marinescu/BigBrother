<?php


require_once dirname(__DIR__, 2) . '/model/bigbrother/bigbrother.class.php';
require_once __DIR__ . '/abstract.class.php';


class BigBrotherMainDashboardWidget extends BigBrotherAbstractDashboardWidget
{
    public $cssBlockClass = 'bigbrother-widget bigbrother-widget--main';

    public function render()
    {
        // Load BigBrother etc
        $this->initialize();

        // Make sure the authorization and property selection was completed. If not, show a message about needing to authorize first
        $authorized = $this->isAuthorized();
        if ($authorized !== true) {
            return $authorized;
        }

        // Create an HTML element for period dates in the title bar
        $titleElement = <<<HTML
<div class="bb-widget-title" style="width:100%; display:flex; justify-content: space-between">
    <span>Google Analytics for &lt;Property Name&gt;</span>
    <span id="bb{$this->widget->get('id')}-title-period" class="bb-title-period" style="padding:6px 8px 3px; margin:-6px -6px -3px 0; background-color:#fff; border-radius:3px;">Loading...</span>
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
            <h3 class="bigbrother-block-title">Acquisition sources</h3>
            <div id="bb{$this->widget->get('id')}-acquisition" style="position:relative; height: 250px"></div>
        </div>
        <div class="bigbrother-col bigbrother-block">
            <h3 class="bigbrother-block-title">Most viewed pages</h3>
            <div id="bb{$this->widget->get('id')}-popular-pages" class="bigbrother-report-list" style="position:relative; height: 250px"></div>
        </div>
    </div>
</div>
HTML;
    }
}

return BigBrotherMainDashboardWidget::class;
