<?php
/**
 * Default English Lexicon Entries for BigBrother
 *
 * @package bigbrother
 * @subpackage lexicon
 *
 */
$_lang['bigbrother'] = 'Google Analytics v4 by Big Brother';

/** Dashboard widget types and descriptions */
$_lang['bigbrother.main.name'] = 'Google Analytics - Main';
$_lang['bigbrother.main.desc'] = 'Big Brother - Full-width main widget encompassing visits, key metrics, acquisition and popular pages insights.';
$_lang['bigbrother.visits.name'] = 'Google Analytics - Visits';
$_lang['bigbrother.visits.desc'] = 'Big Brother - Standalone visits widget';
$_lang['bigbrother.metrics.name'] = 'Google Analytics - Key Metrics';
$_lang['bigbrother.metrics.desc'] = 'Big Brother - Standalone key metrics widget';
$_lang['bigbrother.acquisition.name'] = 'Google Analytics - Acquisition';
$_lang['bigbrother.acquisition.desc'] = 'Big Brother - Standalone acquisition widget';
$_lang['bigbrother.popular_pages.name'] = 'Google Analytics - Popular Pages';
$_lang['bigbrother.popular_pages.desc'] = 'Big Brother - Standalone popular pages widget';
$_lang['bigbrother.top_countries.name'] = 'Google Analytics - Top Countries';
$_lang['bigbrother.top_countries.desc'] = 'Big Brother - Standalone top countries widget';

$_lang['bigbrother.this_month'] = 'This month';
$_lang['bigbrother.last_month'] = 'Last month';
$_lang['bigbrother.page_views'] = 'page views';

/** Authorization panel */
$_lang['bigbrother.main_title'] = 'Google Analytics by Big Brother';
$_lang['bigbrother.main_description'] = '<p><b>Welcome to Big Brother.</b> With Big Brother, you can see your Google Analytics information at a glance on the MODX Dashboard. On this page you can configure your integration.</p>';
$_lang['bigbrother.donate'] = 'Donate';
$_lang['bigbrother.powered_by_bigbrother'] = 'Powered by Big Brother';
$_lang['bigbrother.initial_authorization'] = 'Authorization';
$_lang['bigbrother.initial_authorization_description'] = '<p>To access your Google Analytics data, Big Brother needs authorization to access your Google account. Click the button to login and grant read-only access.</p>';
$_lang['bigbrother.authorization_step1desc'] = 'You\'ll pass through modmore\'s servers in order to allow a straightforward authorization process. No authorization keys are stored by modmore; the single-use authentication code is immediately passed back to your site.';
$_lang['bigbrother.sign_in_with_google'] = 'Sign in with Google';
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
$_lang['bigbrother.authorization.failure.missing_id_or_secret'] = 'Missing client ID or Secret. Typically, Big Brother will provide you a default pair of Google Cloud credentials to ease the setup. However it appears those credentials are missing from your installation. These credentials need to be added before you can continue. <br><br><a href="https://docs.modmore.com/en/Open_Source/BigBrother/Custom_oAuth_Credentials.html" target="_blank" rel="noopener" style="font-weight: bold;">Learn more in the Big Brother documentation &raquo;</a>';
$_lang['bigbrother.revoke_authorization'] = 'Revoke authorization';
$_lang['bigbrother.revoke_authorization.confirm'] = 'Are you sure?';
$_lang['bigbrother.revoke_authorization.confirm_text'] = 'Revoking the authorization will remove the current authorization for your account. To continue using Big Brother, you will need to sign in with Google again.';
$_lang['bigbrother.revoke_authorization.success'] = 'Authorization revoked. Please sign in with Google to re-authorize Big Brother.';
$_lang['bigbrother.not_authorized.warning'] = 'Big Brother has not yet been authorized, the authorization was revoked, or a Google Analytics property has not yet been selected. Once authorized and configured, this dashboard widget will show your Google Analytics statistics.';
$_lang['bigbrother.not_authorized.authorize_now'] = 'Authorize now';
$_lang['bigbrother.guzzle_error'] = 'Big Brother requires Guzzle to work, but it cannot be found. Please install the Guzzle7 package (available via the MODX Package Manager) to resolve this issue and continue setting up Big Brother.';

/** Property selection */
$_lang['bigbrother.current_property'] = '<p>Currently using property <b>[[+displayName]]</b> (<code>[[+propertyId]]</code>)</p>';
$_lang['bigbrother.property'] = 'Select the Google Analytics Property';
$_lang['bigbrother.property_desc'] = 'Using the browser below, please select the Google Analytics account and the property to use with Big Brother. Note that this information is cached, so if you have recently added an account or property you may need to clear the cache (via the Manage > Clear Cache menu) before it will appear in the list.';
$_lang['bigbrother.save_property'] = 'Save selected property';
$_lang['bigbrother.save_property.success'] = 'Property set to [[+property_name]]';
$_lang['bigbrother.missing_web_properties'] = 'The authorized Google account does not seem to have any Google Analytics web properties or an error occurred loading the properties. Revoke the authorization and try signing in with a different account.<br><br>In case of an error, you will find more details in the MODX Error Log. Do you use custom oAuth credentials? Make sure the Google Analytics API <em>and</em> Google Analytics Admin API are enabled.';
$_lang['bigbrother.missing_ga4_web_properties'] = 'The selected account does not have any Google Analytics 4 properties. <br><br> To use Google Analytics 3 ("Universal Analytics") properties, please downgrade to Big Brother v1, available from modmore. <a href="https://support.modmore.com/article/233-how-can-i-use-universal-analytics-properties-with-big-brother" target="_blank" rel="noopener">Learn more &raquo;</a>';

/** Lexicons used by the widgets themselves */
$_lang['bigbrother.main_widget'] = 'Big Brother - Full Stats';
$_lang['bigbrother.main_widget_desc'] = 'Shows a complete overview of your connected Google Analytics profile.';
$_lang['bigbrother.widget_title'] = 'Google Analytics for [[+property_name]]';
$_lang['bigbrother.not_found'] = 'BigBrother was not found.';

$_lang['bigbrother.acquisition_sources'] = 'Acquisition sources';
$_lang['bigbrother.acquisition_sources.subtitle'] = 'by page views for first user source';
$_lang['bigbrother.most_viewed_pages'] = 'Most popular pages';
$_lang['bigbrother.most_viewed_pages.subtitle'] = 'by page views';
$_lang['bigbrother.key_metrics'] = 'Key metrics';
$_lang['bigbrother.top_countries'] = 'Top countries';
$_lang['bigbrother.top_countries.subtitle'] = 'by unique users';
$_lang['bigbrother.top_referrers'] = 'Top referring domains';
$_lang['bigbrother.top_referrers.subtitle'] = 'by page views';
$_lang['bigbrother.daily_page_views'] = 'Daily page views';
$_lang['bigbrother.four_weeks_before'] = '4 weeks before';

$_lang['bigbrother.metrics.sessions'] = 'Sessions';
$_lang['bigbrother.metrics.page_views'] = 'Page Views';
$_lang['bigbrother.metrics.unique_visitors'] = 'Unique Visitors';
$_lang['bigbrother.metrics.engagement_rate'] = 'Engagement Rate';
$_lang['bigbrother.metrics.avg_time_on_site'] = 'Avg Time On Site';

/* System settings */
$_lang['setting_bigbrother.native_app_client_id'] = '(Deprecated) OAuth2.0 Client ID (Desktop/Native App)';
$_lang['setting_bigbrother.native_app_client_id_desc'] = 'Provide your Client ID from https://console.developers.google.com/apis/credentials';

$_lang['setting_bigbrother.native_app_client_secret'] = '(Deprecated) OAuth2.0 Client Secret (Desktop/Native App)';
$_lang['setting_bigbrother.native_app_client_secret_desc'] = 'Provide your Client Secret from https://console.developers.google.com/apis/credentials';

$_lang['setting_bigbrother.oauth_client_id'] = 'OAuth2.0 Client ID';
$_lang['setting_bigbrother.oauth_client_id_desc'] = 'OAuth credentials for authorizing your Google account. For details, please see <a href="https://docs.modmore.com/en/Open_Source/BigBrother/Custom_oAuth_Credentials.html">the documentation</a>.';

$_lang['setting_bigbrother.oauth_client_secret'] = 'OAuth2.0 Client Secret';
$_lang['setting_bigbrother.oauth_client_secret_desc'] = 'OAuth credentials for authorizing your Google account. For details, please see <a href="https://docs.modmore.com/en/Open_Source/BigBrother/Custom_oAuth_Credentials.html">the documentation</a>.';

$_lang['setting_bigbrother.oauth_flow'] = 'OAuth2.0 Flow';
$_lang['setting_bigbrother.oauth_flow_desc'] = 'Either <code>native</code> or <code>webapp</code>. The <code>native</code> option is the legacy authorization flow used by Big Brother v2. If you\'ve upgraded from v2, this setting is automatically set to <code>native</code> and uses your <code>bigbrother.native_app_client_id</code> and <code>bigbrother.native_app_client_secret</code> credentials. For Big Brother v3, the <code>webapp</code> authorization flow is used for any new authorizations (including when this setting is set to native!), which goes through an authorization proxy hosted by modmore. It\'s not currently possible to authorize Big Brother v3 with custom oauth credentials.';

$_lang['setting_bigbrother.property_id'] = 'GA4 Property ID';
$_lang['setting_bigbrother.property_id_desc'] = 'This will automatically be filled with your Google Analytics v4 property id when you authorize Big Brother.';

$_lang['setting_bigbrother.refresh_token'] = 'Refresh Token';
$_lang['setting_bigbrother.refresh_token_desc'] = 'The refresh token is managed internally by Big Brother.';

$_lang['setting_bigbrother.assets_url'] = 'Assets URL';
$_lang['setting_bigbrother.assets_url_desc'] = 'The URL to Big Brother\'s assets directory.';

$_lang['setting_bigbrother.assets_path'] = 'Assets Path';
$_lang['setting_bigbrother.assets_path_desc'] = 'The path to Big Brother\'s assets directory.';

$_lang['setting_bigbrother.core_path'] = 'Core Path';
$_lang['setting_bigbrother.core_path_desc'] = 'The path to Big Brother\'s core directory.';

$_lang['setting_bigbrother.scripts_dev'] = 'Load development scripts';
$_lang['setting_bigbrother.scripts_dev_desc'] = 'When enabled uncompressed scripts will be used on the dashboard, useful for debugging and development. <b>You must run <code>npm install</code> inside assets/components/bigbrother/ before enabling this setting to avoid errors.</b>';