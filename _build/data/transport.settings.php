<?php
/**
 * Default settings
 *
 * @package bigbrother
 * @subpackage build
 */

$settings = array();

$settings['bigbrother.native_app_client_id']= $modx->newObject('modSystemSetting');
$settings['bigbrother.native_app_client_id']->fromArray(array(
    'key' => 'bigbrother.native_app_client_id',
    'value' => GAPI_CLIENT_ID,
    'xtype' => 'text-password',
    'namespace' => 'bigbrother',
    'area' => 'Authorization',
),'',true,true);

$settings['bigbrother.native_app_client_secret']= $modx->newObject('modSystemSetting');
$settings['bigbrother.native_app_client_secret']->fromArray(array(
    'key' => 'bigbrother.native_app_client_secret',
    'value' => GAPI_CLIENT_SECRET,
    'xtype' => 'text-password',
    'namespace' => 'bigbrother',
    'area' => 'Authorization',
),'',true,true);

$settings['bigbrother.property_id']= $modx->newObject('modSystemSetting');
$settings['bigbrother.property_id']->fromArray(array(
    'key' => 'bigbrother.property_id',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'bigbrother',
    'area' => 'Authorization',
),'',true,true);

$settings['bigbrother.refresh_token']= $modx->newObject('modSystemSetting');
$settings['bigbrother.refresh_token']->fromArray(array(
    'key' => 'bigbrother.refresh_token',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'bigbrother',
    'area' => 'Authorization',
),'',true,true);

$settings['bigbrother.assets_url']= $modx->newObject('modSystemSetting');
$settings['bigbrother.assets_url']->fromArray(array(
    'key' => 'bigbrother.assets_url',
    'value' => '{assets_url}components/bigbrother/',
    'xtype' => 'textfield',
    'namespace' => 'bigbrother',
    'area' => 'Paths',
),'',true,true);

$settings['bigbrother.assets_path']= $modx->newObject('modSystemSetting');
$settings['bigbrother.assets_path']->fromArray(array(
    'key' => 'bigbrother.assets_path',
    'value' => '{assets_path}components/bigbrother/',
    'xtype' => 'textfield',
    'namespace' => 'bigbrother',
    'area' => 'Paths',
),'',true,true);

$settings['bigbrother.core_path']= $modx->newObject('modSystemSetting');
$settings['bigbrother.core_path']->fromArray(array(
    'key' => 'bigbrother.core_path',
    'value' => '{core_path}components/bigbrother/',
    'xtype' => 'textfield',
    'namespace' => 'bigbrother',
    'area' => 'Paths',
),'',true,true);

$settings['bigbrother.scripts_dev']= $modx->newObject('modSystemSetting');
$settings['bigbrother.scripts_dev']->fromArray(array(
    'key' => 'bigbrother.scripts_dev',
    'value' => '0',
    'xtype' => 'combo-boolean',
    'namespace' => 'bigbrother',
    'area' => 'Paths',
),'',true,true);

return $settings;