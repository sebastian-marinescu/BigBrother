<?php
/**
 * Removes unused settings from Big Brother v1.x
 *
 * @package bigbrother
 * @subpackage build
 */
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
            $setting = $object->xpdo->getObject('modSystemSetting',array('key' => 'bigbrother.'.$key));
            if ($setting !== null) {
                if ($setting->remove() === false) {
                    $object->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[BigBrother] Attempt to remove old setting '.$key.' failed.');
                }
            } else {
                $object->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[BigBrother] '.$key.' setting could not be found, so the setting could not be removed.');
            }
        }

        // Make sure settings that are kept move to the correct area.
        $updateAreaSettings = [
            'native_app_client_id',
            'native_app_client_secret'
        ];
        foreach ($removeSettings as $key) {
            $setting = $object->xpdo->getObject('modSystemSetting', array('key' => 'bigbrother.' . $key));
            if ($setting !== null) {
               $setting->set('area','Authorization');
               $setting->save();
            }
        }

    case xPDOTransport::ACTION_UNINSTALL:

}
return true;