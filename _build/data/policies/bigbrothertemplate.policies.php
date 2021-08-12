<?php
/** @var \modX $modx */

$policies = [];
$policies[1]= $modx->newObject('modAccessPolicy');
$policies[1]->fromArray([
    'name' => 'BigBrother Admin',
    'description' => 'Access Policy for BigBrother that gives access to the authorization manager page. Overwritten on upgrade.',
    'parent' => 0,
    'class' => '',
    'lexicon' => 'bigbrother:permissions',
    'data' => '{"bigbrother_authorize":true}',
], '', true, true);

return $policies;