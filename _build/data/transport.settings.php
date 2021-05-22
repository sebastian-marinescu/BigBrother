<?php
/**
 * Default settings
 *
 * @package bigbrother
 * @subpackage build
 */

$settings = array();

$settings['bigbrother.cache_timeout']= $modx->newObject('modSystemSetting');
$settings['bigbrother.cache_timeout']->fromArray(array(
    'key' => 'bigbrother.cache_timeout',
    'value' => 300,
    'xtype' => 'textfield',
    'namespace' => 'bigbrother',
    'area' => 'Google Analytics for MODx Revolution',
),'',true,true);

$settings['bigbrother.admin_groups']= $modx->newObject('modSystemSetting');
$settings['bigbrother.admin_groups']->fromArray(array(
    'key' => 'bigbrother.admin_groups',
    'value' => 'Administrator',
    'xtype' => 'textfield',
    'namespace' => 'bigbrother',
    'area' => 'Google Analytics for MODx Revolution',
),'',true,true);

$settings['bigbrother.date_begin']= $modx->newObject('modSystemSetting');
$settings['bigbrother.date_begin']->fromArray(array(
    'key' => 'bigbrother.date_begin',
    'value' => 30,
    'xtype' => 'textfield',
    'namespace' => 'bigbrother',
    'area' => 'Google Analytics for MODx Revolution',
),'',true,true);

$settings['bigbrother.date_end']= $modx->newObject('modSystemSetting');
$settings['bigbrother.date_end']->fromArray(array(
    'key' => 'bigbrother.date_end',
    'value' => 'yesterday',
    'xtype' => 'textfield',
    'namespace' => 'bigbrother',
    'area' => 'Google Analytics for MODx Revolution',
),'',true,true);


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

$settings['bigbrother.profile_id']= $modx->newObject('modSystemSetting');
$settings['bigbrother.profile_id']->fromArray(array(
    'key' => 'bigbrother.profile_id',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'bigbrother',
    'area' => 'Authorization',
),'',true,true);

return $settings;