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
	//Mail variables that are dynamically used:
	'IS_SECURITY_UPDATE' 	=> 'Это обновление для системы безопасности.',
	'NOT_AVAILABLE' 		=> 'Недоступен',
	'ADDITIONAL_INFO'		=> 'Дополнительная информация: %s',
));
