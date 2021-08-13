<?php
/**
 * @var modX $modx
 * @var modTransportPackage $transport
 * @var array $options
 */
$modx =& $transport->xpdo;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $modx->log(xPDO::LOG_LEVEL_INFO, 'Installing User Group access to the BigBrother Admin Policy...');

        /* assign policy to admin group */
        $policy = $modx->getObject('modAccessPolicy', ['name' => 'BigBrother Admin']);
        $adminGroup = $modx->getObject('modUserGroup', ['name' => 'Administrator']);
        if ($policy && $adminGroup) {
            /**
             * Check if we need to apply any default accesses
             */
            $access = $modx->getObject('modAccessContext', [
                'target' => 'mgr',
                'principal_class' => 'modUserGroup',
                'principal' => $adminGroup->get('id'),
                'authority' => 9999,
                'policy' => $policy->get('id'),
            ]);
            if (!$access) {
                $modx->log(modX::LOG_LEVEL_WARN, "Administrator user group does not yet have access to the BigBrother policy, so let's add it!.");
                /**
                 * Add the context access to the admin user group
                 */
                $hasMgrAccess = $modx->getCount('modAccessContext', [
                    'target' => 'mgr',
                    'principal_class' => 'modUserGroup',
                    'principal' => $adminGroup->get('id'),
                ]);

                $access = $modx->newObject('modAccessContext');
                $access->fromArray([
                    'target' => 'mgr',
                    'principal_class' => 'modUserGroup',
                    'principal' => $adminGroup->get('id'),
                    'authority' => 9999,
                    'policy' => $policy->get('id'),
                ]);
                if ($access->save()) {
                    $modx->log(modX::LOG_LEVEL_INFO, '- Added a Context Policy for user group' . $adminGroup->get('name') . ' for full BigBrother access.');
                }

            }
            else {
                $modx->log(modX::LOG_LEVEL_INFO, 'As the Administrator user group already has access to the BigBrother Policy, we will not set up any permissions right now.');
            }
        }

        // flush permissions
        $ctxQuery = $modx->newQuery('modContext');
        $ctxQuery->select($modx->getSelectColumns('modContext', '', '', ['key']));
        if ($ctxQuery->prepare() && $ctxQuery->stmt->execute()) {
            $contexts = $ctxQuery->stmt->fetchAll(PDO::FETCH_COLUMN);
            if ($contexts) {
                $serialized = serialize($contexts);
                $modx->exec("UPDATE {$modx->getTableName('modUser')} SET {$modx->escape('session_stale')} = {$modx->quote($serialized)}");
            }
        }
        break;
}
return true;