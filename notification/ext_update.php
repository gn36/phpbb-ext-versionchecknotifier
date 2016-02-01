<?php
/**
 *
 * @package gn36/versionchecknotifier
 * @copyright (c) 2015 Martin Beckmann
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */


namespace gn36\versionchecknotifier\notification;

class ext_update extends base
{
	// Overwrite base in some cases:
	protected $language_key = 'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE';
	protected $language_key_sec = 'VERSIONCHECKNOTIFIER_NOTIFY_EXT_UPDATE_SEC';
	protected $permission = 'a_extensions';

	public static $notification_option = array(
		'lang' 	=> 'VERSIONCHECKNOTIFY_NOTIFY_OPTION_EXT',
		'group'	=> 'NOTIFICATION_GROUP_MISCELLANEOUS',
	);

	public function get_type()
	{
		return 'gn36.versionchecknotifier.notification.type.ext_update';
	}

	public function get_email_template()
	{
		return '@gn36_versionchecknotifier/mail_ext_update';
	}
}
