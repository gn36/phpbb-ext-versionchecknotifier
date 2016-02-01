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
	'VERSIONCHECKNOTIFY_NOTIFY_OPTION_EXT' 		=> 'A new version of an extension is available.',
	'VERSIONCHECKNOTIFY_NOTIFY_OPTION_PHPBB'	=> 'A new phpBB version is available.',
));
