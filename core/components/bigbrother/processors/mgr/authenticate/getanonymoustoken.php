<?php
/**
 * OAuth login sequence - grab an anonymous token
 *
 * @package bigbrother
 * @subpackage processors
 * @var BigBrother $ga
 */
$ga =& $modx->bigbrother;
$callbackUrl = $scriptProperties['callback_url'];

$response['text'] = '';
$response['trail'][] = array('text' => $modx->lexicon('bigbrother.bd_authorize'));
$response['success'] = true;

$oAuthClient = $ga->loadOAuth();
if (!$oAuthClient) {
    $response['success'] = false;
    $response['text'] = $modx->lexicon('bigbrother.err_load_oauth');
    return $modx->toJSON($response);
}

$authParams = array(
    'scope' => 'https://www.googleapis.com/auth/analytics.readonly'
);
$response['url'] = $oAuthClient->getAuthenticationUrl($ga->oauthEndpoint, $callbackUrl, $authParams);

return $modx->toJSON($response);