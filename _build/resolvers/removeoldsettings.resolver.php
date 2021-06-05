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

        $settings = [
            'admin_groups',
            'cache_timeout',
            'date_begin',
            'date_end'
        ];

        foreach ($settings as $key) {
            $setting = $object->xpdo->getObject('modSystemSetting',array('key' => 'bigbrother.'.$key));
            if ($setting !== null) {
                if ($setting->remove() === false) {
                    $object->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[BigBrother] Attempt to remove old setting '.$key.' failed.');
                }
            } else {
                $object->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[BigBrother] '.$key.' setting could not be found, so the setting could not be removed.');
            }

        }


    case xPDOTransport::ACTION_UNINSTALL:

}
return true;