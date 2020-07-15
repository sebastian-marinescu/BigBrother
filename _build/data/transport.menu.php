<?php
/**
 * Adds modMenu into package
 *
 * @package bigbrother
 * @subpackage build
 */


/* load menu into action */
$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'parent' => 'components',
    'text' => 'bigbrother',
    'description' => 'bigbrother.menu_desc',
    'icon' => 'images/icons/plugin.gif',
    'menuindex' => '0',
    'params' => '',
    'handler' => '',
    'namespace' => 'bigbrother',
    'action' => 'report',
),'',true,true);

return $menu;