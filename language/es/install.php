<?php
/**
*
* gn36/versionchecknotifier [Spanish]
*
* @package language
* @copyright (c) 2015 gn#36
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

// Define categories and permission types
$lang = array_merge($lang, array(
	'WRONG_PHP_VERSION' 		=> 'Su versión de PHP no es compatible con esta extensión.',
	'WRONG_PHPBB_VERSION' 		=> 'Su versión de phpBB no es compatible con esta extensión.',
	'WRONG_EXTENSION_VERSION' 	=> 'La versión de la extensión <strong>%s</strong> no es compatible con esta extensión.',
	'MISSING_DEPENDENCIES' 		=> 'Faltan dependencias de esta extensión. Por favor, use composer para instalar todas las dependencias que faltan, o utilice un paquete de instalación completo.',
	'MISSING_EXTENSION'			=> 'Para instalar esta extensión, se requiere la extensión <strong>%s</strong>.',
));
