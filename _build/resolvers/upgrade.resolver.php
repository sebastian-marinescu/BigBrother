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
            'show_visits_on_dashboard',
            'account',
            'account_name',
            'total_account',
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

        // Remove v1 menu item
        $menu = $modx->getObject('modMenu', [
            'text'  =>  'BigBrother',
            'namespace' => 'bigbrother'
        ]);
        if ($menu instanceof modMenu) {
            $modx->log(xPDO::LOG_LEVEL_WARN, 'Removing Big Brother v1 menu item...');
            $success = $menu->remove();

            if (!$success) {
                $modx->log(xPDO::LOG_LEVEL_ERROR, 'Unable to remove Big Brother v1 menu item!');
            }
            else {
                $modx->log(xPDO::LOG_LEVEL_WARN, 'Successfully removed Big Brother v1 menu item.');
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
                $dashboardId = $placement->get('dashboard');
                $position = $placement->get('rank');

                $modx->log(xPDO::LOG_LEVEL_WARN,'Replacing Big Brother v1 widget with Big Brother v2 widget on dashboard #' . $placement->get('dashboard'));
                $oldWidget->remove();

                // Create new placement to avoid strange behaviour of old placement disappearing
                $newPlacement = $modx->newObject('modDashboardWidgetPlacement');
                $newPlacement->set('dashboard', $dashboardId);
                $newPlacement->set('widget', $newWidget->get('id'));
                $newPlacement->set('rank', $position);
                $success = $newPlacement->save();
                if ($success) {
                    $modx->log(xPDO::LOG_LEVEL_WARN, 'Successfully replaced Big Brother v1 widget.');
                }
                else {
                    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Something went wrong replacing the Big Brother v1 widget!');
                }
            }
        }


        // Try to gracefully handle the v2 > v3 upgrade with new credentials
        $token = $modx->getOption('bigbrother.refresh_token');
        $flow = $modx->getOption('bigbrother.oauth_flow');
        $setting = $modx->getObject('modSystemSetting', array('key' => 'bigbrother.oauth_flow'));
        if ($setting) {
            if (!empty($token) && empty($flow)) {
                $setting->set('value', 'native');
                $modx->log(xPDO::LOG_LEVEL_WARN, 'Existing refresh_token found, configured the oauth_flow to <b>native</b>.');
                $modx->log(xPDO::LOG_LEVEL_WARN, '<b>Important: the next time you authorize Big Brother, it will go through the new webapp proxy flow and previous (custom) oauth credentials will no longer apply.');
            } elseif (empty($flow)) {
                $setting->set('value', 'webapp');
                $modx->log(xPDO::LOG_LEVEL_WARN, 'No existing refresh_token found, configured the oauth_flow to <b>webapp</b>.');
            }
            $setting->save();
        }
        else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, 'Error: could not find the bigbrother.oauth_flow setting to smoothly handle potential v2 > v3 upgrades! You may need to re-authorize after installation if this was an upgrade.');
        }

    case xPDOTransport::ACTION_UNINSTALL:

}
return true;