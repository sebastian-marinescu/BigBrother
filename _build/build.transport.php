<?php
/**
 * bigbrother build script
 *
 * @package bigbrother
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

function getSnippetContent($path, $name, $debug = false) {
    $name = ($debug) ? 'debug.'. $name .'.php' : $name .'.php';
    $filename = $path . $name;
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}

if (!defined('MOREPROVIDER_BUILD')) {
    /* define version */
    define('PKG_NAME', 'BigBrother');
    define('PKG_NAME_LOWER', strtolower(PKG_NAME));
    define('PKG_VERSION', '1.5.0');
    define('PKG_RELEASE', 'dev1');

    /* load modx */
    require_once dirname(dirname(__FILE__)) . '/config.core.php';
    require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
    $modx= new modX();
    $modx->initialize('mgr');
    $modx->setLogLevel(modX::LOG_LEVEL_INFO);
    $modx->setLogTarget('ECHO');

    echo '<pre>';
    flush();
    $targetDirectory = dirname(dirname(__FILE__)) . '/_packages/';
}
else {
    $targetDirectory = MOREPROVIDER_BUILD_TARGET;
}

$root = dirname(dirname(__FILE__)).'/';
$sources= array (
    'debug' => true,
    'root' => $root,
    'files' => $root .'files/',
    'build' => $root .'_build/',
    'data' => $root .'_build/data/',
    'resolvers' => $root .'_build/resolvers/',
    'validators' => $root .'_build/validators/',
    'core' => $root.'core/components/'.PKG_NAME_LOWER,
    'snippets' => $root.'core/components/'.PKG_NAME_LOWER.'/elements/snippets/',
    'assets' => $root.'assets/components/'.PKG_NAME_LOWER,
    'lexicon' => $root . 'core/components/'.PKG_NAME_LOWER.'/lexicon/',
    'docs' => $root.'core/components/'.PKG_NAME_LOWER.'/docs/',
    'model' => $root.'core/components/'.PKG_NAME_LOWER.'/model/',
);
unset($root);

if (file_exists(__DIR__ . '/build.config.php')) {
    require_once __DIR__ . '/build.config.php';
}
if (!defined('GAPI_CLIENT_ID')) {
    echo "Missing GAPI_CLIENT_ID constant, make sure to provide a _build/build.config.php file based on the provided simple.\n";
    return;
}

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->directory = $targetDirectory;
$builder->createPackage(PKG_NAME_LOWER,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER,false,true,'{core_path}components/'.PKG_NAME_LOWER.'/', '{assets_path}components/'.PKG_NAME_LOWER.'/');
$modx->getService('lexicon','modLexicon');
$modx->lexicon->load('bigbrother:default');

/* Create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_NAME);
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in category.'); flush();

$vehicle= $builder->createVehicle($category, array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => false,
));

$modx->log(modX::LOG_LEVEL_INFO, 'Adding file resolvers to category...');

$vehicle->validate('php', array(
    'source' => $sources['validators'] . 'requirements.script.php'
));
$vehicle->resolve('file',array(
    'source' => $sources['core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));

$vehicle->resolve('file',array(
    'source' => $sources['assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';",
));

$vehicle->resolve('php',array(
    'source' => $sources['resolvers'] . 'setupoptions.resolver.php',
));

$modx->log(modX::LOG_LEVEL_INFO,'Packaged in resolvers.'); flush();
$builder->putVehicle($vehicle);

/* Load system settings */
$modx->log(modX::LOG_LEVEL_INFO,'Packaging in System Settings...');
$settings = include $sources['data'].'transport.settings.php';
if (empty($settings)) $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in settings.');
$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'key',
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
);
foreach ($settings as $setting) {
    $vehicle = $builder->createVehicle($setting,$attributes);
    $builder->putVehicle($vehicle);
}
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($settings).' system settings.'); flush();
unset($settings,$setting,$attributes);

/* Load action/menu */
$menus = include $sources['data'].'transport.menu.php';
$vehicle= $builder->createVehicle($menu,array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'text',
));
$builder->putVehicle($vehicle);
unset($vehicle,$action);
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in menu.'); flush();

/* Load Dashboard Widgets */
$modx->log(modX::LOG_LEVEL_INFO,'Packaging in Dashboard Widgets...');
$widgets = include $sources['data'].'transport.dashboard_widgets.php';
if (empty($widgets)) $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in widgets.');
$attributes = array(
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => array ('name'),
);

foreach ($widgets as $widget) {
    $vehicle = $builder->createVehicle($widget,$attributes);
    $builder->putVehicle($vehicle);
}
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($widgets).' widgets.'); flush();
unset($widgets,$widget,$attributes);

/* Pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
    'setup-options' => array(
        'source' => $sources['build'].'setup.options.php',
    ),
));
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in package attributes.'); flush();

$modx->log(modX::LOG_LEVEL_INFO,'Packing...'); flush();
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

