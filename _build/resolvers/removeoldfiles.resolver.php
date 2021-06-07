<?php
/**
 * Removes unused files from Big Brother v1.x
 *
 * @package bigbrother
 * @subpackage build
 */
function removeDirectory($key) {
    $files = glob($key . '/*');
    foreach ($files as $file) {
        is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    rmdir($key);
}

$modx = $transport->xpdo;
if ($transport->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $assetsPath = $modx->getOption('assets_path');
            $corePath = $modx->getOption('core_path');
            if (!$assetsPath || !$corePath) {
                $modx->log(xPDO::LOG_LEVEL_ERROR, '[BigBrother] Unable to remove old files due to missing setting paths.');
                return true;
            }

            $assetsPath = $assetsPath . 'components/bigbrother/';
            $corePath = $corePath . 'components/bigbrother/';

            // Remove old files from BB v1.x
            $removeFiles = [
                $corePath . 'index.class.php',
                $corePath . 'controllers/authenticate.class.php',
                $corePath . 'controllers/report.class.php',
                $corePath . 'controllers/widget.class.php',
                $corePath . 'model/OAuth2/',
                $corePath . 'processors/mgr/authenticate/',
                $corePath . 'processors/mgr/manage/',
                $corePath . 'processors/mgr/report/',
                $assetsPath . 'dashboard/',
                $assetsPath . 'images/arrow-down.png',
                $assetsPath . 'images/arrow-up.png',
                $assetsPath . 'images/clear.png',
                $assetsPath . 'images/delete.png',
                $assetsPath . 'images/equal.png',
                $assetsPath . 'images/loading.gif',
                $assetsPath . 'images/options.png',
                $assetsPath . 'mgr/authenticate/',
                $assetsPath . 'mgr/cmp/',
                $assetsPath . 'mgr/lib/',
            ];
            foreach ($removeFiles as $key) {
                // Remove dir and contents
                if (is_dir($key)) {
                    removeDirectory($key);
                } // Remove single file
                else if (is_file($key)) {
                    unlink($key);
                }
            }

        case xPDOTransport::ACTION_UNINSTALL:

    }
}
return true;