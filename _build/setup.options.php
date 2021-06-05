<?php
/**
* Build the setup options form.
*
* @package bigbrother
* @subpackage build
*/

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
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
<b>You\'re using Big Brother v2, which only supports the new Google Analytics v4. If you must use the older Google Analytics v3, please install Big Brother v1.5 instead.</b>
In April 2021 we launched a <a href="https://modmore.com/extras/bigbrother/crowdfunding/" target="_blank" rel="noopener">crowdfunding campaign for Big Brother v2</a>, to support Google Analytics v4 and MODX 3. If you enjoy Big Brother, please consider contributing to make v2 even better. Thank you!
</p>';

return $output;
