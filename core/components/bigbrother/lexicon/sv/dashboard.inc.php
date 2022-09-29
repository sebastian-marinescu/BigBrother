<?php
/**
 * Dashboard Swedish Lexicon Entries for BigBrother
 *
 * @package bigbrother
 * @subpackage lexicon
 * @author Joakim Nyman <joakim@fractalwolfe.com>
 *
 */
$_lang['bigbrother.name']               = 'Google Analytics';
$_lang['bigbrother.desc']               = 'Big Brother Infopanel beskrivning';

$_lang['bigbrother.notlogged_desc']     = 'Ditt Analytics-konto har ännu inte konfigurerats.<br />Klicka på knappen nedanför för att gå till konfigureringssidan.';
$_lang['bigbrother.notlogged_btn']      = 'Ställ in ditt Analytics-konto';

$_lang['bigbrother.desc_markup']        = "<h3>{title}<span>{date_begin} - {date_end}</span></h3><div class=\"account-infos\"><button onclick=\"Ext.getCmp('modx-panel-bigbrother').redirect(); return false;\" class=\"inline-button green\">Visa full rapport</button>{name}<span>{id}</span></div>";
$_lang['bigbrother.desc_title']         = 'Översikt';

$_lang['bigbrother.visits']             = 'Besök';
$_lang['bigbrother.visitors']           = 'Besökare';
$_lang['bigbrother.traffic_sources']    = 'Trafikkällor';

// End of life banner
$_lang['bigbrother.eol_banner.heading'] = 'Upgrade to Google Analytics v4 [[+link]]';
$_lang['bigbrother.eol_banner.content'] = 'You are currently using <em>Universal Analytics</em> which Google have announced will <strong>[[+link]]</strong>. Big Brother v1.5 is now deprecated and will only receive security updates. To continue seeing analytics on your dashboard, please upgrade to Big Brother v3 after Google Analytics v4 is installed.';
$_lang['bigbrother.eol_banner.button'] = 'Upgrade';