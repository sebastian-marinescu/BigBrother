<?php


require_once dirname(__DIR__, 2) . '/model/bigbrother/bigbrother.class.php';
require_once __DIR__ . '/abstract.class.php';


class BigBrotherPopularPagesDashboardWidget extends BigBrotherAbstractDashboardWidget
{
    public $cssBlockClass = 'bigbrother-widget bigbrother-widget--popular-pages';

    public function render()
    {
        $this->initialize();

        // Make sure the authorization and property selection was completed. If not, show a message about needing to authorize first
        $authorized = $this->isAuthorized();
        if ($authorized !== true) {
            return $authorized;
        }

        // Adjust the name shown in the widget title bar - alternatively we could also extend process() instead of
        // render() for more control, but that may require more maintenance to keep cross-version compatible
        $this->widget->set('name', $this->modx->lexicon('bigbrother.most_viewed_pages') . ' <span class="bigbrother-widget-subtitle">' . $this->modx->lexicon('bigbrother.most_viewed_pages.subtitle') . '</span>');

        // Register the initialisation of the chart within this widget
        $this->controller->addHtml(<<<HTML
<script>
Ext.onReady(function() {
    let charts = [];
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
        <div id="bb{$this->widget->get('id')}-popular-pages" class="bigbrother-report-list" style="position:relative; height: 250px"></div>
    </div>
</div>
HTML;
    }
}

return BigBrotherPopularPagesDashboardWidget::class;
