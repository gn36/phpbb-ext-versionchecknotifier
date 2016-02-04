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
	//Mail variables that are dynamically used:
	'IS_SECURITY_UPDATE' 	=> 'Esta é uma atualização de segurança.',
	'NOT_AVAILABLE' 		=> 'Não disponível',
	'ADDITIONAL_INFO'		=> 'Informação adicional: %s',
));
