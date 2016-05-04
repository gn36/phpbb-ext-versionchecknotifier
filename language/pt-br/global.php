<?php
/**
 *
 * gn36/versionchecknotifier [pt]
 *
 * @package language
 * @copyright (c) 2016 gn#36 (Martin Beckmann)
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	// Notifications
	'VERSIONCHECKNOTIFIER_NOTIFY_BASE'				=> 'Isto é uma notificação básica de versão.',
	'VERSIONCHECKNOTIFIER_NOTIFY_BASE_SEC'			=> 'Isto é uma notificação básica de segurança.',
	'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE'		=> 'Uma <strong>nova versão do phpBB</strong> está disponível.',
	'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE_SEC' 	=> 'Uma <strong>atualização de segurança</strong> do <strong>phpBB</strong> está disponível.',
	'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE'		=> 'Uma <strong>nova versão</strong> da extensão <strong>%1$s</strong> está disponível.',
	'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE_SEC'	=> 'Uma <strong>atualização de segurança</strong> da extensão <strong>%1$s</strong> está disponível.',

	'REASON_UPDATE'		=> 'Nova versão: %2$s, atualmente instalada: %3$s.',
	'REASON_UPDATE_DL'	=> 'Nova versão: %2$s, atualmente instalada: %3$s. <a href="%4$s">Baixar a nova versão</a>',

	'UPDATE_AVAILABLE' => 'Atualização disponível',

	'INVALID_NOTIFICATION_ID_REDIRECT' => 'A notificação não foi encontrado; o redirecionamento não é possível.'
));
