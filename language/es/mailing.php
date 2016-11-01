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
	//Mail variables that are dynamically used:
	'IS_SECURITY_UPDATE' 	=> 'Esta es una actualización de seguridad.',
	'NOT_AVAILABLE' 		=> 'No disponible',
	'ADDITIONAL_INFO'		=> 'Información adicional: %s',
));
