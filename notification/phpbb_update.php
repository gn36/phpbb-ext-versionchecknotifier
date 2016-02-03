<?php
/**
 *
 * @package gn36/versionchecknotifier
 * @copyright (c) 2015 Martin Beckmann
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */


namespace gn36\versionchecknotifier\notification;

class phpbb_update extends base
{
	// Overwrite base in some cases:
	protected $language_key = 'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE';
	protected $language_key_sec = 'VERSIONCHECKNOTIFIER_NOTIFY_PHPBB_UPDATE_SEC';
	protected $notify_icon = 'notify_phpbb';
	protected $permission = 'a_board';

	public static $notification_option = array(
		'lang' 	=> 'VERSIONCHECKNOTIFY_NOTIFY_OPTION_PHPBB',
		'group'	=> 'NOTIFICATION_GROUP_MISCELLANEOUS',
	);

	public function get_type()
	{
		return 'gn36.versionchecknotifier.notification.type.phpbb_update';
	}

	public function get_email_template()
	{
		return '@gn36_versionchecknotifier/mail_phpbb_update';
	}
}
