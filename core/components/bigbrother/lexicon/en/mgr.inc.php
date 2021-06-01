<?php
/**
 * MGR English Lexicon Entries for BigBrother
 *
 * @package bigbrother
 * @subpackage lexicon
 *
 */

$_lang['bigbrother.main_title'] = 'Google Analytics by Big Brother';
$_lang['bigbrother.main_description'] = '<p><b>Welcome to Big Brother.</b> With Big Brother, you can see your Google Analytics information at a glance on the MODX Dashboard. On this page you can configure your integration.</p>';

$_lang['bigbrother.donate'] = 'Donate';
$_lang['bigbrother.powered_by_bigbrother'] = 'Powered by BigBrother';
$_lang['bigbrother.initial_authorization'] = 'Authorization';
$_lang['bigbrother.initial_authorization_description'] = '<p>To access your Google Analytics data, Big Brother needs authorization to access your Google account in three simple steps. The integration requires only read-only access, authorization secrets will never leave your site. </p>';
$_lang['bigbrother.authorization_step1desc'] = 'Click the button to start the sign in process in a new window. Choose your Google Account if prompted, and authorize the Big Brother integration. Copy the provided authorization token.';
$_lang['bigbrother.authorization_step2desc'] = 'Copy and paste the authorization token into the field below. ';
$_lang['bigbrother.code'] = 'Paste the Authorization Code';
$_lang['bigbrother.loading'] = 'Loading...';
$_lang['bigbrother.verify_code'] = 'Verify Code';
$_lang['bigbrother.oauth_error'] = 'Received an authentication error. This may indicate the provided code or a stored token is no longer valid.';
$_lang['bigbrother.error.enter_auth_code'] = 'Please copy the authorization code provided when signing in with Google, and paste it into the Authorization Code field.';
$_lang['bigbrother.error.select_a_property'] = 'Please select an account and then a property from the list.';
$_lang['bigbrother.error.invalid_property'] = 'Property does not seem valid or a communication error occurred.';

$_lang['bigbrother.authorized'] = 'Authorized';
$_lang['bigbrother.authorized_desc'] = 'Successfully authorized, Big Brother can access your Google Analytics reports. To re-authorize or use a different account, revoke the authorization.';
$_lang['bigbrother.authorization'] = 'Authorization';
$_lang['bigbrother.authorization.success'] = 'Successfully authorized.';
$_lang['bigbrother.authorization.failure.unexpected_response'] = 'Unexpected response.';
$_lang['bigbrother.revoke_authorization'] = 'Revoke authorization';
$_lang['bigbrother.revoke_authorization.confirm'] = 'Are you sure?';
$_lang['bigbrother.revoke_authorization.confirm_text'] = 'Revoking the authorization will remove the current authorization for your account. To continue using Big Brother, you will need to sign in with Google again.';
$_lang['bigbrother.revoke_authorization.success'] = 'Authorization revoked. Please sign in with Google to re-authorize Big Brother.';
$_lang['bigbrother.not_authorized.warning'] = 'Big Brother has not yet been authorized, the authorization was revoked, or a Google Analytics property has not yet been selected. Once authorized and configured, this dashboard widget will show your Google Analytics statistics.';
$_lang['bigbrother.not_authorized.authorize_now'] = 'Authorize now';

$_lang['bigbrother.current_property'] = '<p>Currently using property <b>[[+displayName]]</b> (<code>[[+propertyId]]</code>)</p>';
$_lang['bigbrother.property'] = 'Select the Google Analytics Property';
$_lang['bigbrother.property_desc'] = 'Using the browser below, please select the Google Analytics account and the property to use with Big Brother. Note that this information is cached, so if you have just added an account or property, it may take a few minutes to appear in the list.';
$_lang['bigbrother.save_property'] = 'Save selected property';
$_lang['bigbrother.save_property.success'] = 'Property set to [[+property_name]]';

$_lang['bigbrother.main_widget'] = 'Big Brother - Full Stats';
$_lang['bigbrother.main_widget_desc'] = 'Shows a complete overview of your connected Google Analytics profile.';
$_lang['bigbrother.widget_title'] = 'Google Analytics for [[+property_name]]';
$_lang['bigbrother.not_found'] = 'BigBrother was not found.';

$_lang['bigbrother.acquisition_sources'] = 'Acquisition sources';
$_lang['bigbrother.most_viewed_pages'] = 'Most viewed pages';