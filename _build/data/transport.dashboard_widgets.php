<?php
/**
 * Dashboard Widgets
 *
 * @package bigbrother
 * @subpackage build
 */

$widgets = [];

$widgets[1]= $modx->newObject('modDashboardWidget');
$widgets[1]->fromArray([
    'name' => 'bigbrother.main_widget',
    'description' => 'bigbrother.main_widget_desc',
    'type' => 'file',
    'size' => 'full',
    'content' => '[[++core_path]]components/bigbrother/elements/widgets/main.widget.php',
    'namespace' => 'bigbrother',
    'lexicon' => 'bigbrother:mgr',
], '', true, true);

return $widgets;