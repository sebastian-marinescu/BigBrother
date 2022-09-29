<?php
/**
 * Dashboard French Lexicon Entries for bigbrother
 *
 * @package bigbrother
 * @subpackage lexicon
 *
 */
$_lang['bigbrother.name']               = 'Google Analytics';
$_lang['bigbrother.desc']               = 'Description du tableau de bord de Big Brother';

$_lang['bigbrother.notlogged_desc']     = 'Votre compte n\'a pas encore été configuré<br/>Utilisez le bouton ci-dessous pour acceder à la page de configuration.';
$_lang['bigbrother.notlogged_btn']      = 'Configurer votre compte Google Analytics';

$_lang['bigbrother.desc_markup']        = '<h3>{title}<span>{date_begin} - {date_end}</span></h3><div class=\"account-infos\"><button onclick=\"Ext.getCmp(\'modx-panel-bigbrother\').redirect(); return false;\" class=\"inline-button green\">Voir le rapport complet</button>{name}<span>{id}</span></div>';
$_lang['bigbrother.desc_title']         = 'Vue d\'ensemble';

$_lang['bigbrother.visits']             = 'Visites';
$_lang['bigbrother.visitors']           = 'Visiteurs';
$_lang['bigbrother.traffic_sources']    = 'Sources de trafic';

// End of life banner
$_lang['bigbrother.eol_banner.heading'] = 'Upgrade to Google Analytics v4 [[+link]]';
$_lang['bigbrother.eol_banner.content'] = 'You are currently using <em>Universal Analytics</em> which Google have announced will <strong>[[+link]]</strong>. Big Brother v1.5 is now deprecated and will only receive security updates. To continue seeing analytics on your dashboard, please upgrade to Big Brother v3 after Google Analytics v4 is installed.';
$_lang['bigbrother.eol_banner.button'] = 'Upgrade';