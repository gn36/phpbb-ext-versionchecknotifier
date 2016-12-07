<?php
/**
 *
 * gn36/versionchecknotifier [Spanish]
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
	'VERSIONCHECKNOTIFIER_NOTIFY_BASE'				=> 'Esta es una notificación genérica de verificación de versión.',
	'VERSIONCHECKNOTIFIER_NOTIFY_BASE_SEC'			=> 'Esta es una notificación genérica de verificación de seguridad de versión.',
	'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE'		=> 'Una <strong>nueva versión de phpBB</strong> está disponible.',
	'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE_SEC' 	=> 'Una <strong>actualización de seguridad</strong> de <strong>phpBB</strong> está disponible.',
	'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE'		=> 'Una <strong>nueva versión</strong> de la extensión <strong>%1$s</strong> está disponible.',
	'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE_SEC'	=> 'Una <strong>actualización de seguridad</strong> de la extensión <strong>%1$s</strong> está disponible.',

	'REASON_UPDATE'		=> 'Nueva versión: %2$s, actualmente instalada: %3$s.',
	'REASON_UPDATE_DL'	=> 'Nueva versión: %2$s, actualmente instalada: %3$s. <a href="%4$s">Descargar nueva versión</a>',

	'UPDATE_AVAILABLE' => 'Actualización disponible',

	'INVALID_NOTIFICATION_ID_REDIRECT' => 'No se ha encontrado la notificación, la redirección no es posible.'
));
