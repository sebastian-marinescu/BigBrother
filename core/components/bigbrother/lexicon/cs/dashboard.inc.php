<?php
/**
 * Dashboard Czech Lexicon Entries for BigBrother
 * translated by Jiri Pavlicek jiri@pavlicek.cz
 *
 * @package bigbrother
 * @subpackage lexicon
 * @language cz
 *
 */
$_lang['bigbrother.name']               = 'Google Analytics';
$_lang['bigbrother.desc']               = 'Big Brother na nástěnku';

$_lang['bigbrother.notlogged_desc']     = 'Váš účet Google Analytics dosud nebyl nastaven.<br />Kliknutím na tlačítko níže vstupte na konfigurační stránku.';
$_lang['bigbrother.notlogged_btn']      = 'Nastavte Váš účet Google Analytics';

$_lang['bigbrother.desc_markup']        = "<h3>{title}<span>{date_begin} - {date_end}</span></h3><div class=\"account-infos\"><button onclick=\"Ext.getCmp('modx-panel-bigbrother').redirect(); return false;\" class=\"inline-button green\">Zobrazit úplný přehled</button>{name}<span>{id}</span></div>";
$_lang['bigbrother.desc_title']         = 'Celkový přehled';

$_lang['bigbrother.visits']             = 'Návštěvy';
$_lang['bigbrother.visitors']           = 'Návštěvníci';
$_lang['bigbrother.traffic_sources']    = 'Zdroje návštěvnosti';

// End of life banner
$_lang['bigbrother.eol_banner.heading'] = 'Upgrade to Google Analytics v4 [[+link]]';
$_lang['bigbrother.eol_banner.content'] = 'You are currently using <em>Universal Analytics</em> which Google have announced will <strong>[[+link]]</strong>. Big Brother v1.5 is now deprecated and will only receive security updates. To continue seeing analytics on your dashboard, please upgrade to Big Brother v3 after Google Analytics v4 is installed.';
$_lang['bigbrother.eol_banner.button'] = 'Upgrade';