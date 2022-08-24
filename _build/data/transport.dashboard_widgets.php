<?php
/**
 * Dashboard Widgets
 *
 * @package bigbrother
 * @subpackage build
 */

$widgets = [];

$widgets[0]= $modx->newObject('modDashboardWidget');
$widgets[0]->fromArray([
    'name' => 'bigbrother.main.name',
    'description' => 'bigbrother.main.desc',
    'type' => 'file',
    'size' => 'full',
    'content' => '[[++core_path]]components/bigbrother/elements/widgets/main.class.php',
    'namespace' => 'bigbrother',
    'lexicon' => 'bigbrother:default',
], '', true, true);

$widgets[1]= $modx->newObject('modDashboardWidget');
$widgets[1]->fromArray([
    'name' => 'bigbrother.visits.name',
    'description' => 'bigbrother.visits.desc',
    'type' => 'file',
    'size' => 'half',
    'content' => '[[++core_path]]components/bigbrother/elements/widgets/visitsline.class.php',
    'namespace' => 'bigbrother',
    'lexicon' => 'bigbrother:default',
], '', true, true);

$widgets[2]= $modx->newObject('modDashboardWidget');
$widgets[2]->fromArray([
    'name' => 'bigbrother.metrics.name',
    'description' => 'bigbrother.metrics.desc',
    'type' => 'file',
    'size' => 'half',
    'content' => '[[++core_path]]components/bigbrother/elements/widgets/metrics.class.php',
    'namespace' => 'bigbrother',
    'lexicon' => 'bigbrother:default',
], '', true, true);

$widgets[3]= $modx->newObject('modDashboardWidget');
$widgets[3]->fromArray([
    'name' => 'bigbrother.acquisition.name',
    'description' => 'bigbrother.acquisition.desc',
    'type' => 'file',
    'size' => 'half',
    'content' => '[[++core_path]]components/bigbrother/elements/widgets/acquisition.class.php',
    'namespace' => 'bigbrother',
    'lexicon' => 'bigbrother:default',
], '', true, true);

$widgets[4]= $modx->newObject('modDashboardWidget');
$widgets[4]->fromArray([
    'name' => 'bigbrother.popular_pages.name',
    'description' => 'bigbrother.popular_pages.desc',
    'type' => 'file',
    'size' => 'half',
    'content' => '[[++core_path]]components/bigbrother/elements/widgets/popularpages.class.php',
    'namespace' => 'bigbrother',
    'lexicon' => 'bigbrother:default',
], '', true, true);

$widgets[5]= $modx->newObject('modDashboardWidget');
$widgets[5]->fromArray([
    'name' => 'bigbrother.top_countries.name',
    'description' => 'bigbrother.top_countries.desc',
    'type' => 'file',
    'size' => 'half',
    'content' => '[[++core_path]]components/bigbrother/elements/widgets/topcountries.class.php',
    'namespace' => 'bigbrother',
    'lexicon' => 'bigbrother:default',
], '', true, true);

$widgets[6]= $modx->newObject('modDashboardWidget');
$widgets[6]->fromArray([
    'name' => 'bigbrother.top_referrers.name',
    'description' => 'bigbrother.top_referrers.desc',
    'type' => 'file',
    'size' => 'half',
    'content' => '[[++core_path]]components/bigbrother/elements/widgets/topreferrers.class.php',
    'namespace' => 'bigbrother',
    'lexicon' => 'bigbrother:default',
], '', true, true);

return $widgets;