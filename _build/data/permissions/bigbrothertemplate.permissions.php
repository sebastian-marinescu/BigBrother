<?php

/** @var \modX $modx */
$permissions = [];
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'bigbrother_authorize',
    'description' => 'bigbrother.permission.authorize',
    'value' => true,
));

return $permissions;