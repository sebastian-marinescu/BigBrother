<?php
/**
 * Removes unused settings from Big Brother v1.x
 *
 * @package bigbrother
 * @subpackage build
 */

$modx = $transport->xpdo;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:

        // Remove old settings from BB v1.x
        $removeSettings = [
            'admin_groups',
            'cache_timeout',
            'date_begin',
            'date_end',
            'show_metas_on_dashboard',
            'show_pies_on_dashboard',
            'show_visits_on_dashboard'
        ];
        foreach ($removeSettings as $key) {
            $setting = $modx->getObject('modSystemSetting',array('key' => 'bigbrother.'.$key));
            if ($setting !== null) {
                if ($setting->remove() === false) {
                    $modx->log(xPDO::LOG_LEVEL_ERROR,'[BigBrother] Attempt to remove old setting '.$key.' failed.');
                }
            }
        }

        // Make sure settings that are kept move to the correct area.
        $updateAreaSettings = [
            'native_app_client_id',
            'native_app_client_secret'
        ];
        foreach ($removeSettings as $key) {
            $setting = $modx->getObject('modSystemSetting', array('key' => 'bigbrother.' . $key));
            if ($setting !== null) {
               $setting->set('area','Authorization');
               $setting->save();
            }
        }
        
        // Make sure v1 widget is replaced with v2 automatically
        $oldWidget = $modx->getObject('modDashboardWidget', [
            'name' => 'bigbrother.name'
        ]);
        $newWidget = $modx->getObject('modDashboardWidget', [
            'name' => 'bigbrother.main.name',
        ]);
        if ($oldWidget && $newWidget) {
            foreach ($modx->getIterator('modDashboardWidgetPlacement', [
                'widget' => $oldWidget->get('id')
            ]) as $placement) {
                $modx->log(xPDO::LOG_LEVEL_WARN,'Replacing Big Brother v1 widget with Big Brother v2 widget on dashboard #' . $placement->get('dashboard'));
                $placement->set('widget', $newWidget->get('id'));
                $placement->save();
            }

            $modx->log(xPDO::LOG_LEVEL_WARN, 'Removing Big Brother v1 widget');
            $oldWidget->remove();
        }

    case xPDOTransport::ACTION_UNINSTALL:

}
return true;