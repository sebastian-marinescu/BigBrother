<?php
/**
 * Dashboard Russian Lexicon Entries for BigBrother
 *
 * @package bigbrother
 * @subpackage lexicon
 * @author Anton Slobodchuk, http://www.gecko-studio.ru/
 *
 */
$_lang['bigbrother.name']               = 'Google Analytics';
$_lang['bigbrother.desc']               = 'Описание панели Big Brother';

$_lang['bigbrother.notlogged_desc']     = 'Аккаунт Google Analytics еще не настроен.<br />Нажмите на кнопку внизу, чтобы перейти на страницу с настройкой.';
$_lang['bigbrother.notlogged_btn']      = 'Настройка вашего аккаунта Google Analytics';

$_lang['bigbrother.desc_markup']        = "<h3>{title}<span>{date_begin} - {date_end}</span></h3><div class=\"account-infos\"><button onclick=\"Ext.getCmp('modx-panel-bigbrother').redirect(); return false;\" class=\"inline-button green\">Детальный отчёт</button>{name}<span>{id}</span></div>";
$_lang['bigbrother.desc_title']         = 'Обзор';

$_lang['bigbrother.visits']             = 'Посещения';
$_lang['bigbrother.visitors']           = 'Посетители';
$_lang['bigbrother.traffic_sources']    = 'Источники трафика';

// End of life banner
$_lang['bigbrother.eol_banner.heading'] = 'Upgrade to Google Analytics v4 [[+link]]';
$_lang['bigbrother.eol_banner.content'] = 'You are currently using <em>Universal Analytics</em> which Google have announced will <strong>[[+link]]</strong>. Big Brother v1.5 is now deprecated and will only receive security updates. To continue seeing analytics on your dashboard, please upgrade to Big Brother v3 after Google Analytics v4 is installed.';
$_lang['bigbrother.eol_banner.button'] = 'Upgrade';