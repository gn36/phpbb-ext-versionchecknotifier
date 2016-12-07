<?php
/**
 *
 * gn36/versionchecknotifier [ru]
 *
 * @package language
 * @copyright (c) 2016 gn#36 (Martin Beckmann), Rubinovi4
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
	'VERSIONCHECKNOTIFIER_NOTIFY_BASE'				=> 'Общее уведомление Versioncheck.',
	'VERSIONCHECKNOTIFIER_NOTIFY_BASE_SEC'			=> 'Уведомление безопасности Versioncheck.',
	'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE'		=> 'Доступна новая версия <strong>phpBB</strong>.',
	'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE_SEC' 	=> 'Доступно <strong>обновление безопасности phpBB</strong>.',
	'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE'		=> 'Доступна новая версия расширения <strong>%1$s</strong>.',
	'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE_SEC'	=> 'Доступно <strong>обновление безопасности</strong> расширения <strong>%1$s</strong>.',

	'REASON_UPDATE'		=> 'Новая версия: %2$s, установленная версия: %3$s.',
	'REASON_UPDATE_DL'	=> 'Новая версия: %2$s, установленная версия: %3$s. <a href="%4$s">Обновить</a>',

	'UPDATE_AVAILABLE' => 'Доступно обновление',

	'INVALID_NOTIFICATION_ID_REDIRECT' => 'Обновление не найдено, редирект не представляется возможным.'
));
