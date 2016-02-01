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
	'VERSIONCHECKNOTIFY_NOTIFY_OPTION_EXT' 		=> 'Eine neue Version einer installierten Erweiterung ist verfügbar.',
	'VERSIONCHECKNOTIFY_NOTIFY_OPTION_PHPBB'	=> 'Eine neue phpBB Version ist verfügbar.',
));
