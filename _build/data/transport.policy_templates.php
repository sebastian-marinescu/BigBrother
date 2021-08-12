<?php

/** @var \modX $modx */
$templates = [];
$templates[1]= $modx->newObject('modAccessPolicyTemplate');
$templates[1]->fromArray([
    'id' => 1,
    'name' => 'BigBrotherTemplate',
    'description' => 'Policy Template for access to the BigBrother authorization page.',
    'lexicon' => 'bigbrother:permissions',
    'template_group' => 1,
]);

$permissions = include dirname(__FILE__).'/permissions/bigbrothertemplate.permissions.php';
if (is_array($permissions)) {
    $templates[1]->addMany($permissions);
} else {
    $modx->log(modX::LOG_LEVEL_ERROR,'Could not load BigBrotherTemplate Permissions.');
}

$policies = include dirname(__FILE__).'/policies/bigbrothertemplate.policies.php';
if (is_array($policies)) {
    $templates[1]->addMany($policies);
} else {
    $modx->log(modX::LOG_LEVEL_ERROR,'Could not load BigBrotherTemplate Policies.');
}

return $templates;