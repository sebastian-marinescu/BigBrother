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
$_lang['bigbrother.revoke_authorization'] = 'Revoke authorization';
$_lang['bigbrother.revoke_authorization.confirm'] = 'Are you sure?';
$_lang['bigbrother.revoke_authorization.confirm_text'] = 'Revoking the authorization will remove the current authorization for your account. To continue using Big Brother, you will need to sign in with Google again.';
$_lang['bigbrother.current_property'] = '<p>Currently using property <b>[[+displayName]]</b> (<code>[[+propertyId]]</code>)</p>';
$_lang['bigbrother.property'] = 'Property';
$_lang['bigbrother.property_desc'] = 'Select the account and profile ';
$_lang['bigbrother.save_property'] = 'Save selected property';

return;
/* Alert */
$_lang['bigbrother.alert_failed'] = 'Failed';

/* Action buttons - modAB */
$_lang['bigbrother.revoke_authorization'] = 'Revoke authorization';
$_lang['bigbrother.revoke_permission'] = 'Revoke permissions?';
$_lang['bigbrother.revoke_permission_msg'] = 'By revoking permission, you\'ll have to go through the setup process again to authorize MODx to use Google Analytics\'s APIs.<br />
                                                      <br />
                                                      Are you sure you want to revoke permissions?
                                                      <span class="warning"><strong>Note:</strong> All override setting account assigned to users will be erased as well.</span>';

/* Authenticate */
$_lang['bigbrother.account_authentication_desc'] = 'Use the button below to login to Google to authorize MODX to access your Google Analytics Data.</p>
<p style="margin-top: 1em;">The authorization page of Google will open in a popup. After logging in and accepting the requested authentication and terms, Google will provide you with a code. Copy the presented code into the field below to verify the authorization. After that, you will be prompted to choose which Analytics Profile to use for the report.';
$_lang['bigbrother.bd_root_desc'] = 'Verifying if SimpleXML and cURL PHP extensions are activated...';
$_lang['bigbrother.bd_root_crumb_text'] = 'Verify prerequisites';

$_lang['bigbrother.verify_prerequisite_settings'] = 'Verify Prerequisite Settings';
$_lang['bigbrother.verify_authentication'] = 'Verify Authentication';
$_lang['bigbrother.authorize'] = 'Authorize with Google';
$_lang['bigbrother.authorize_success'] = 'Successfully authorized with Google! Please wait...';
$_lang['bigbrother.code_label'] = 'Authentication Code';
$_lang['bigbrother.code_label_under'] = 'Paste the Authentication Code provided by Google into this field, and click Verify Authentication to complete the authorization and choose the desired account.';

/* Oauth complete */
$_lang['bigbrother.bd_oauth_complete_in_progress'] = 'Authentication in progress...';
$_lang['bigbrother.bd_oauth_authorize'] = 'Authorize';
$_lang['bigbrother.oauth_select_account'] = 'Select an account...';
$_lang['bigbrother.oauth_btn_select_account'] = 'Select this account and view the report';

/* Oauth response */
$_lang['bigbrother.err_load_oauth'] = 'Could not load the required OAuth class. Please reinstall the component or contact the webmaster.';

/* mgr - breadcrumbs */
$_lang['bigbrother.bd_authorize'] = 'Authorize';
$_lang['bigbrother.bd_choose_an_account'] = 'Choose an Account';

/* mgr - Authenticate Ajax response strings */
$_lang['bigbrother.class_simplexml'] = '<strong>It seems that <a href="http://us3.php.net/manual/en/book.simplexml.php">SimpleXML</a> is not compiled into your version of PHP.<br />
                                                      This component is required for this plugin to function correctly.</strong>';
$_lang['bigbrother.function_curl'] = '<strong>It seems that <a href="http://www.php.net/manual/en/book.curl.php">cURL</a> is not compiled into your version of PHP.<br />
                                                      This component is required for this plugin to function correctly.</strong>';
$_lang['bigbrother.redirect_to_google'] = 'Redirecting to Google, please wait...';
$_lang['bigbrother.authentification_complete'] = 'Authentication complete.</p>
                                                      <p>Select the account you wish to use by default in the list below.<br />
                                                      At any time, you will be able to select another account in the dashboard.';
$_lang['bigbrother.account_set_succesfully_wait'] = 'Account set successfully! Please wait...';
$_lang['bigbrother.not_authorized_to'] = 'You do not have permission to do this operation. Please contact the site administrator.';

/* Reports */
$_lang['bigbrother.desc_markup'] = '<h3>{title}<span>{date_begin} - {date_end}</span></h3><div class="account-infos">{name}<span>{id}</span></div>';
$_lang['bigbrother.loading'] = 'Loading...';

/* Content Overview */

$_lang['bigbrother.content'] = 'Content';
$_lang['bigbrother.content_overview'] = 'Content Overview';
$_lang['bigbrother.site_content'] = 'Site Content';
$_lang['bigbrother.visits_comparisons'] = 'Visits compared to the previous month';

/* Audience Overview */
$_lang['bigbrother.audience'] = 'Audience';
$_lang['bigbrother.audience_overview'] = 'Audience Overview';
$_lang['bigbrother.audience_visits'] = 'Visits';

$_lang['bigbrother.demographics'] = 'Demographics';
$_lang['bigbrother.language'] = 'Language';
$_lang['bigbrother.country'] = 'Country / Territory';

$_lang['bigbrother.system'] = 'System';
$_lang['bigbrother.browser'] = 'Browser';
$_lang['bigbrother.operating_system'] = 'Operating System';
$_lang['bigbrother.service_provider'] = 'Service Provider';

$_lang['bigbrother.mobile'] = 'Mobile';
$_lang['bigbrother.screen_resolution'] = 'Screen Resolution';

/* Traffic sources Overview */
$_lang['bigbrother.traffic_sources'] = 'Traffic Sources';
$_lang['bigbrother.traffic_sources_overview'] = 'Traffic Sources Overview';
$_lang['bigbrother.traffic_sources_visits'] = 'Visits';

$_lang['bigbrother.organic_source'] = 'Search Engines';
$_lang['bigbrother.keyword'] = 'Search Engine Keywords';
$_lang['bigbrother.referral_source'] = 'Referring Sites';
$_lang['bigbrother.landing_page'] = 'Landing Page';

/* Misc - Dimensions */
$_lang['bigbrother.none'] = '(none)';
$_lang['bigbrother.direct_traffic'] = 'Direct Traffic';

$_lang['bigbrother.search_traffic'] = 'organic';
$_lang['bigbrother.referral_traffic'] = 'referral';

$_lang['bigbrother.search_traffic_replace_with'] = 'Search Engines';
$_lang['bigbrother.referral_traffic_replace_with'] = 'Referring Sites';

/* Misc - Metrics */
$_lang['bigbrother.visits_and_uniques'] = 'Visits and Uniques';
$_lang['bigbrother.avg_time_on_site'] = 'Avg. Time on Site';
$_lang['bigbrother.page'] = 'Page';
$_lang['bigbrother.pagetitle'] = 'Page Title';
$_lang['bigbrother.pageviews'] = 'Pageviews';
$_lang['bigbrother.pageviews_per_visit'] = 'Pages / Visit';
$_lang['bigbrother.unique_pageviews'] = 'Unique Pageviews';
$_lang['bigbrother.bounce_rate'] = 'Bounce Rate';
$_lang['bigbrother.visits'] = 'Visits';
$_lang['bigbrother.visitors'] = 'Visitors';
$_lang['bigbrother.percent_visits'] = '% Visits';
$_lang['bigbrother.exit_rate'] = '% Exits';
$_lang['bigbrother.new_visits'] = 'New Visits';
$_lang['bigbrother.new_visits_in_percent'] = '% New Visits';
$_lang['bigbrother.direct_traffic'] = 'Direct Traffic';
$_lang['bigbrother.search_engines'] = 'Search Engines';

/* Options panel */
$_lang['bigbrother.google_analytics_options'] = 'Google Analytics Options';
$_lang['bigbrother.options'] = 'Options';
$_lang['bigbrother.save_settings'] = 'Save Settings';
$_lang['bigbrother.general_options'] = 'General Options';
$_lang['bigbrother.dashboard_options'] = 'Dashboard Options';
$_lang['bigbrother.account_options'] = 'Account Options';

/* Options panel - cmp options */
$_lang['bigbrother.accounts_list'] = 'Accounts List';
$_lang['bigbrother.accounts_list_desc'] = 'Select the account you want to use for your report';

$_lang['bigbrother.date_range'] = 'Date Range';
$_lang['bigbrother.date_range_desc'] = 'Select the date range for your reports';

$_lang['bigbrother.15_days'] = '15 days';
$_lang['bigbrother.30_days'] = '30 days';
$_lang['bigbrother.45_days'] = '45 days';
$_lang['bigbrother.60_days'] = '60 days';

$_lang['bigbrother.today'] = 'Today';
$_lang['bigbrother.yesterday'] = 'Yesterday';

$_lang['bigbrother.report_end_date'] = 'Report End Date';
$_lang['bigbrother.report_end_date_desc'] = 'Select the date to which the reports should end';

$_lang['bigbrother.caching_time'] = 'Caching Time';
$_lang['bigbrother.caching_time_desc'] = 'How long report results should be saved in local cache (in seconds)';

$_lang['bigbrother.admin_groups'] = 'Administrator Groups';
$_lang['bigbrother.admin_groups_desc'] = 'Comma separated list of Administrator Group Names who have access to the current options panel';

/* Options panel - dashboard options */
$_lang['bigbrother.show_visits_on_dashboard'] = 'Visits';
$_lang['bigbrother.show_visits_on_dashboard_desc'] = 'Show visits on Dashboard';

$_lang['bigbrother.show_metas_on_dashboard'] = 'Informations';
$_lang['bigbrother.show_metas_on_dashboard_desc'] = 'Show meta informations on Dashboard';

$_lang['bigbrother.show_pies_on_dashboard'] = 'Visitors and Traffic sources';
$_lang['bigbrother.show_pies_on_dashboard_desc'] = 'Show Visitors and Traffic Sources pie charts on the dashboard';

/* Account Options */
$_lang['bigbrother.user_account_default'] = "default";
$_lang['bigbrother.account_options_desc'] = "<p>Bigbrother uses a default pre-selected account for the Analytics reports.<br />
                                                      However, it is possible to assign a specific Google Analytics account per MODx user to override the default account settings.</p>
                                                      <p>A user assigned to a specific account will use it for both the CMP and the dashboard.<br />
                                                      Use the grid below to select an account by clicking on the value in the account column. A list of all available accounts will be shown.</p>
                                                      <div class=\"warning\"><p><strong>Note:</strong> The user list shows all users regardless of wether they have access rights to the manager or not.</p></div>";
$_lang['bigbrother.search_placeholder'] = "Search...";
$_lang['bigbrother.rowheader_name'] = "Name";
$_lang['bigbrother.rowheader_account'] = "Account";
