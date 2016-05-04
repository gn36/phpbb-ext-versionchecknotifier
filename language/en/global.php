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
	'VERSIONCHECKNOTIFIER_NOTIFY_BASE'				=> 'This is a generic Versioncheck Notification.',
	'VERSIONCHECKNOTIFIER_NOTIFY_BASE_SEC'			=> 'This is a generic Versioncheck Security Notification.',
	'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE'		=> 'A <strong>new phpBB Version</strong> is available.',
	'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE_SEC' 	=> 'A <strong>security update</strong> of <strong>phpBB</strong> is available.',
	'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE'		=> 'A <strong>new version</strong> of the Extension <strong>%1$s</strong> is available.',
	'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE_SEC'	=> 'A <strong>security update</strong> of the Extension <strong>%1$s</strong> is available.',

	'REASON_UPDATE'		=> 'New version: %2$s, currently installed: %3$s.',
	'REASON_UPDATE_DL'	=> 'New version: %2$s, currently installed: %3$s. <a href="%4$s">Download new version</a>',

	'UPDATE_AVAILABLE' => 'Update available',

	'INVALID_NOTIFICATION_ID_REDIRECT' => 'The notification was not found, the redirect is not possible.'
));
