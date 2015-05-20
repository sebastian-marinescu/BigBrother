<?php
/**
 * Set current user assigned account
 *
 * @package bigbrother
 * @subpackage processors
 *
 * @var BigBrother $ga
 * @var array $scriptProperties
 * @var modX $modx
 */
$ga =& $modx->bigbrother;
$response['success'] = false;

/* Get the edited user object */
$user = $modx->getObject('modUser', $scriptProperties['user']);
$defaultValue = $modx->lexicon('bigbrother.user_account_default');
$remove = false;

if ($scriptProperties['account'] == $defaultValue) {
    $remove = true;
}

/** @var modUserSetting $account */
$account = $modx->getObject('modUserSetting', array(
    'user' => (int)$scriptProperties['user'],
    'key' => 'bigbrother.account'
));

if ($remove) {
    if ($account) {
        $account->remove();
    }
}
else {
    if (!$account) {
        $account = $modx->newObject('modUserSetting');
        $account->fromArray(array(
            'user' => (int)$scriptProperties['user'],
            'key' => 'bigbrother.account',
            'xtype' => 'textfield',
            'namespace' => 'bigbrother'
        ), '', true);
    }
    $account->set('value', $scriptProperties['account']);
    $account->save();
}

/** @var modUserSetting $accountName */
$accountName = $modx->getObject('modUserSetting', array(
    'user' => (int)$scriptProperties['user'],
    'key' => 'bigbrother.account_name'
));

if ($remove) {
    if ($accountName) {
        $accountName->remove();
    }
}
else {
    if (!$accountName) {
        $accountName = $modx->newObject('modUserSetting');
        $accountName->fromArray(array(
            'user' => (int)$scriptProperties['user'],
            'key' => 'bigbrother.account_name',
            'xtype' => 'textfield',
            'namespace' => 'bigbrother'
        ), '', true);
    }
    $accountName->set('value', $scriptProperties['accountName']);
    $accountName->save();
}

$response['success'] = true;

return $modx->toJSON($response);