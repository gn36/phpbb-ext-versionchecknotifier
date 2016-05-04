<?php
/**
 *
 * gn36/versionchecknotifier [de]
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
	'VERSIONCHECKNOTIFIER_NOTIFY_BASE'				=> 'Generische Versioncheck Nachricht.',
	'VERSIONCHECKNOTIFIER_NOTIFY_BASE_SEC'			=> 'Generische Versioncheck Sicherheitsnachricht',
	'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE'		=> 'Eine <strong>neue phpBB Version</strong> ist verfügbar',
	'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE_SEC' 	=> 'Ein <strong>Sicherheitsupdate</strong> für <strong>phpBB</strong> ist verfügbar.',
	'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE'		=> 'Eine <strong>neue Version</strong> der Erweiterung <strong>%1$s</strong> ist verfügbar.',
	'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE_SEC'	=> 'Ein <strong>Sicherheitsupdate</strong> für die Erweiterung <strong>%1$s</strong> ist verfügbar.',

	'REASON_UPDATE'		=> 'Neue Version: %2$s. Derzeit installiert: %3$s.',
	'REASON_UPDATE_DL'	=> 'Neue Version: %2$s. Derzeit installiert: %3$s. <a href="%4$s">Neue Version herunterladen</a>',

	'UPDATE_AVAILABLE' => 'Update verfügbar',

	'INVALID_NOTIFICATION_ID_REDIRECT' => 'Die Weiterleitung ist nicht möglich, die Benachrichtigung wurde nicht gefunden.'
));
