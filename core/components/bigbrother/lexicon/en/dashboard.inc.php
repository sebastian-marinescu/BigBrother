<?php
/**
 * Dashboard English Lexicon Entries for BigBrother
 *
 * @package bigbrother
 * @subpackage lexicon
 *
 */
$_lang['bigbrother.name'] = 'Google Analytics';
$_lang['bigbrother.desc'] = 'Big Brother Dashboard Description';

$_lang['bigbrother.notlogged_desc'] = 'Your Analytics account has not yet been configured.<br />Click the button below to access the configuration page.';
$_lang['bigbrother.notlogged_btn'] = 'Setup your Analytics account';

$_lang['bigbrother.desc_markup'] = '<h3>{title}<span>{date_begin} - {date_end}</span></h3><div class=\"account-infos\"><button onclick=\"Ext.getCmp(\'modx-panel-bigbrother\').redirect(); return false;\" class=\"inline-button green\">View full report</button>{name}<span>{id}</span></div>';
$_lang['bigbrother.desc_title'] = 'Overview';

$_lang['bigbrother.visits'] = 'Visits';
$_lang['bigbrother.visitors'] = 'Visitors';
$_lang['bigbrother.traffic_sources'] = 'Traffic Sources';

// End of life banner
$_lang['bigbrother.eol_banner.heading'] = 'Upgrade to Google Analytics v4 [[+link]]';
$_lang['bigbrother.eol_banner.content'] = 'You are currently using <em>Universal Analytics</em> which Google have announced will <strong>[[+link]]</strong>. Big Brother v1.5 is now deprecated and will only receive security updates. To continue seeing analytics on your dashboard, please upgrade to Big Brother v3 after Google Analytics v4 is installed.';
$_lang['bigbrother.eol_banner.button'] = 'Upgrade';