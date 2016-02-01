<?php
/**
 *
 * @package gn36/versionchecknotifier
 * @copyright (c) 2015 Martin Beckmann
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */


namespace gn36\versionchecknotifier\notification;

class base extends \phpbb\notification\type\base
{
	protected $language_key = 'VERSIONCHECKNOTIFIER_NOTIFY_BASE';
	protected $language_key_sec = 'VERSIONCHECKNOTIFIER_NOTIFY_BASE_SEC';

	// The permission to check to find users / send message
	protected $permission = 'a_this_is_a_nonexistent_permission';

	public static $notification_option = array(
		'lang' 	=> 'VERSIONCHECKNOTIFY_NOTIFY_OPTION',
		'group'	=> 'NOTIFICATION_GROUP_MISCELLANEOUS',
	);

	public function get_type()
	{
		return 'gn36.versionchecknotifier.notification.type.base';
	}

	public function is_available()
	{
		return $this->auth->acl_get($this->permission);
	}

	public static function get_item_id($notification_data)
	{
		// String -> unique numeric id is never really pleasant
		$id = gmp_init(substr(md5($notification_data['ext_name'] . $notification_data['version']), 0, 16), 16);
		return gmp_intval(gmp_div_r($id, gmp_init(100000000-1)));
	}

	public static function get_item_parent_id($notification_data)
	{
		// Parent of an extension version is the extension itself:
		$id = gmp_init(substr(md5($notification_data['ext_name']), 0, 16), 16);
		return gmp_intval(gmp_div_r($id, gmp_init(100000000-1)));
	}

	public function find_users_for_notification($notification_data, $options = array())
	{
		$options = array_merge(array(
			'ignore_users' => array(),
		), $options);

		//TODO: This may fail if this administrator permission is denied using "never"!
		$users = $this->auth->acl_get_list(false, $this->permission);
		$users = (!empty($users[0][$this->permission])? $users[0][$this->permission] : array());

		if (empty($users))
		{
			// No administrators???
			return array();
		}
		$users = array_unique($users);

		return $this->check_user_notification_options($users, $options);
	}

	public function get_avatar()
	{
		//return $this->user_loader->get_avatar($this->get_data('user_id'));
		//TODO: This may not work if we are somewhere in a virtual subfolder (-> Mod Rewrite or similar)
		return "<img class='avatar' alt='update available' src='{$this->phpbb_root_path}ext/gn36/versionchecknotifier/fixtures/notify_icon.png' />";
	}

	public function get_title()
	{
		$ext_name = $this->get_data('name');
		$version  = $this->get_data('new_version');
		$security = $this->get_data('security');

		return $this->user->lang($security ? $this->language_key_sec : $this->language_key, $ext_name, $version);
	}

	function users_to_query()
	{
		return array();
	}

	public function get_url()
	{
		if ($url = $this->get_data('announcement_url'))
		{
			return $url;
		}
		if ($url = $this->get_data('download_url'))
		{
			return $url;
		}
		// Not useful, but at least a valid link
		return append_sid("{$this->phpbb_root_path}index.{$this->php_ext}");
	}

	public function get_redirect_url()
	{
		return $this->get_url();
	}

	public function get_email_template()
	{
		//return '@gn36_versionchecknotifier/mail_notify';
		return false;
	}

	//public function get_reference()

	public function get_email_template_variables()
	{
		// TODO: EMAIL_SIG
		return array(
			'EXTENSION' => $this->get_data('name'),
			'VERSION'	=> $this->get_data('version'),
			'SITENAME' => $this->config['sitename'],
			'U_INDEX' => generate_board_url() . "/index.{$this->php_ext}",
			//TODO: How to actually personalize this?
		);
	}

	public function create_insert_array($notification_data, $pre_create_data = array())
	{
		// This should be the extension name (or phpbb):
		if (isset($notification_data['name']))
		{
			$this->set_data('name', $notification_data['name']);
		}
		else
		{
			$this->set_data('name', 'phpbb');
		}

		if (isset($notification_data['version']))
		{
			$this->set_data('version', $notification_data['version']);
		}
		else
		{
			$this->set_data('version', 0);
		}

		if (isset($notification_data['old_version']))
		{
			$this->set_data('old_version', $notification_data['old_version']);
		}

		if (isset($notification_data['text']))
		{
			$this->set_data('text', $notification_data['text']);
		}

		if (isset($notification_data['security']))
		{
			$this->set_data('security', $notification_data['security']);
		}

		if (isset($notification_data['download_url']))
		{
			$this->set_data('download_url', $notification_data['download_url']);
		}

		if (isset($notification_data['announcement_url']))
		{
			$this->set_data('announcement_url', $notification_data['announcement_url']);
		}

		return parent::create_insert_array($notification_data, $pre_create_data);
	}

	/**
	 * Update a notification
	 *
	 * @param array $notification_data Data specific for this type that will be updated
	 */
	public function update_notifications($notification_data)
	{
		$old_notifications = array();
		$sql = 'SELECT n.user_id
			FROM ' . $this->notifications_table . ' n, ' . $this->notification_types_table . ' nt
			WHERE n.notification_type_id = ' . (int) $this->notification_type_id . '
				AND n.item_id = ' . static::get_item_id($notification_data) . '
				AND nt.notification_type_id = n.notification_type_id
				AND nt.notification_type_enabled = 1';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$old_notifications[] = $row['user_id'];
		}
		$this->db->sql_freeresult($result);

		// Find the new users to notify
		$notifications = $this->find_users_for_notification($notification_data);

		// Find the notifications we must delete
		$remove_notifications = array_diff($old_notifications, array_keys($notifications));

		// Find the notifications we must add
		$add_notifications = array();
		foreach (array_diff(array_keys($notifications), $old_notifications) as $user_id)
		{
			$add_notifications[$user_id] = $notifications[$user_id];
		}

		// Add the necessary notifications
		$this->notification_manager->add_notifications_for_users($this->get_type(), $notification_data, $add_notifications);

		// Remove the necessary notifications
		if (!empty($remove_notifications))
		{
			$sql = 'DELETE FROM ' . $this->notifications_table . '
				WHERE notification_type_id = ' . (int) $this->notification_type_id . '
					AND item_id = ' . static::get_item_id($notification_data) . '
					AND ' . $this->db->sql_in_set('user_id', $remove_notifications);
			$this->db->sql_query($sql);
		}

		// return true to continue with the update code in the notifications service (this will update the rest of the notifications)
		return true;
	}
}
