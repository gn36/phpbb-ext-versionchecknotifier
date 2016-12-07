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
	'VERSIONCHECKNOTIFY_NOTIFY_OPTION_EXT' 		=> 'Una nueva versión de una extensión está disponible.',
	'VERSIONCHECKNOTIFY_NOTIFY_OPTION_PHPBB'	=> 'Una nueva versión de phpBB está disponible.',
));
