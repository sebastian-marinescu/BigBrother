<?php
/**
* Build the setup options form.
*
* @package bigbrother
* @subpackage build
*/
/* Default value */
$values = array(
    'admin_groups' => 'Administrator',
    'cache_timeout' => 300,
);
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $setting = $modx->getObject('modSystemSetting',array('key' => 'bigbrother.admin_groups'));
        if ($setting != null) { $values['admin_groups'] = $setting->get('value'); }
        unset($setting);

        $setting = $modx->getObject('modSystemSetting',array('key' => 'bigbrother.cache_timeout'));
        if ($setting != null) { $values['cache_timeout'] = $setting->get('value'); }
        unset($setting);
    break;
    case xPDOTransport::ACTION_UNINSTALL: break;
}

$output = '
<style type="text/css">
    .field-desc{
        color: #A0A0A0;
        font-size: 11px;
        font-style: italic;
        line-height: 1;
        margin: 5px -15px 0;
        padding: 0 15px;
    }
    .field-desc.sep{
        border-bottom: 1px solid #E0E0E0;
        margin-bottom: 15px;
        padding-bottom: 15px;
    }
    .bb-v2 {
        padding: 15px;
        border-radius: 5px;
        background: #bee2ff;
        color: #10436d;
        margin-bottom: 15px;
    }
</style>';

$output .= '<p class="bb-v2">
<b>You\'re using Big Brother v1, which only supports Google Analytics v3 (Universal Analytics) and MODX 2.7+.</b>
If you use Google Analytics v4 or MODX 3, you need to upgrade to Big Brother v2. <a href="https://support.modmore.com/article/228-what-is-universal-analytics-google-analytics-4" target="_blank" rel="noopener">Learn more about the difference.</a></p>
<p>Do you like Big Brother? <a href="https://modmore.com/extras/bigbrother/donate/" target="_blank" rel="noopener">Please consider a donation</a> to support our open source work. Thank you!</p>';

$output .= '<label for="admin_groups">Administrator Groups:</label>
<input type="text" name="admin_groups" id="admin_groups" width="300" value="'.$values['admin_groups'].'" />
<div class="field-desc sep">Comma separated list of User Group who have access of Big Brothers options on the CMP</div>';

$output .= '<label for="cache_timeout">Cache:</label>
<input type="text" name="cache_timeout" id="cache_timeout" width="300" value="'.$values['cache_timeout'].'" />
<div class="field-desc">How long should report results should be cached locally (in seconds)</div>';

return $output;
