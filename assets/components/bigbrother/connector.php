<?php
$basePath = dirname(__DIR__, 3);

require_once $basePath . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('bigbrother.core_path', null, $modx->getOption('core_path') . 'components/bigbrother/');
$bigBrother = $modx->getService('bigbrother', 'BigBrother', $corePath . 'model/bigbrother/');
$modx->request->handleRequest([
    'processors_path' => $bigBrother->config['processors_path'],
]);
