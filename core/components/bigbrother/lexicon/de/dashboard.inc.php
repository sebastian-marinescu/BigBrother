<?php
/**
 * Dashboard German Lexicon Entries for BigBrother
 *
 * @package bigbrother
 * @subpackage lexicon
 * @author Martin Gartner
 */
$_lang['bigbrother.name']               = 'Google Analytics';
$_lang['bigbrother.desc']               = 'BigBrother Dashboard Beschreibung';

$_lang['bigbrother.notlogged_desc']     = 'Ihr Analytics Konto ist noch nicht konfiguriert.<br />Klicken Sie auf den unten stehenden Button um die Konfigurationsseite aufzurufen.';
$_lang['bigbrother.notlogged_btn']      = 'Analytics Konto einrichten';

$_lang['bigbrother.desc_markup']        = '<h3>{title}<span>{date_begin} - {date_end}</span></h3><div class=\"account-infos\"><button onclick=\"Ext.getCmp(\'modx-panel-bigbrother\').redirect(); return false;\" class=\"inline-button green\">Vollst&auml;ndiger Bericht</button>{name}<span>{id}</span></div>';
$_lang['bigbrother.desc_title']         = '&Uuml;bersicht';

$_lang['bigbrother.visits']             = 'Besuche';
$_lang['bigbrother.visitors']           = 'Besucher';
$_lang['bigbrother.traffic_sources']    = 'Besucherquellen';

// End of life banner
$_lang['bigbrother.eol_banner.heading'] = 'Upgrade to Google Analytics v4 [[+link]]';
$_lang['bigbrother.eol_banner.content'] = 'You are currently using <em>Universal Analytics</em> which Google have announced will <strong>[[+link]]</strong>. Big Brother v1.5 is now deprecated and will only receive security updates. To continue seeing analytics on your dashboard, please upgrade to Big Brother v3 after Google Analytics v4 is installed.';
$_lang['bigbrother.eol_banner.button'] = 'Upgrade';