<?php
/**
 * Define the MODX path constants necessary for installation
 *
 * @package samples
 * @subpackage build
 */

// Go to https://console.developers.google.com and create a project
// Under APIs & Auth > Credentials, create a Native Application (other) Client ID.
// Put the Client ID and Client Secret here to embed those into the built package.
define('GAPI_CLIENT_ID', 'Your Google API Client ID Here');
define('GAPI_CLIENT_SECRET', 'Your Google API Client Secret Here');

define('MODX_BASE_PATH', dirname(dirname(dirname(__FILE__))).'/');
define('MODX_CORE_PATH', MODX_BASE_PATH . 'core/');
define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');
define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'assets/');

define('MODX_BASE_URL','/modx/');
define('MODX_CORE_URL', MODX_BASE_URL . 'core/');
define('MODX_MANAGER_URL', MODX_BASE_URL . 'manager/');
define('MODX_CONNECTORS_URL', MODX_BASE_URL . 'connectors/');
define('MODX_ASSETS_URL', MODX_BASE_URL . 'assets/');