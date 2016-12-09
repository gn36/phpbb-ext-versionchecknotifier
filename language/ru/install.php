<?php
/**
*
* @package language [ru]
* @copyright (c) 2015 gn#36, Rubinovi4
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
	'WRONG_PHP_VERSION' 		=> 'Ваша PHP версия несовместима с данным расширением.',
	'WRONG_PHPBB_VERSION' 		=> 'Ваша версия PHPBB несовместима с данным расширением.',
	'WRONG_EXTENSION_VERSION' 	=> 'Версия <strong>%s</strong> расширения несовместима с данным расширением.',
	'MISSING_DEPENDENCIES' 		=> 'Зависимости данного расширения отсутствуют. Пожалуйста, установите недостающие зависимости или используйте полный пакет установки.',
	'MISSING_EXTENSION'			=> 'Чтобы установить данное расширение, требуется расширение <strong>%s</strong>.',
));
