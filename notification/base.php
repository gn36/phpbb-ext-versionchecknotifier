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
	protected $notify_icon = 'notify_icon';

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

	/**
	 * calculate decimal from hex
	 * @param string $val hex value
	 */
	protected static function bchex2dec($val)
	{
		if (strlen($val) == 1)
		{
			return hexdec($val);
		}
		$head = substr($val, 0, -1);
		$tail = substr($val, -1);
		return bcadd(bcmul(16, self::bchex2dec($head)), hexdec($tail));
	}

	public static function get_item_id($notification_data)
	{
		// String -> unique numeric id is never really pleasant
		//TODO: BCMOD replacement wieder einbauen
		$id = substr(md5($notification_data['ext_name'] . $notification_data['version']), 0, 16);
		return intval(bcmod(self::bchex2dec($id), 10000000-1));

	}

	public static function get_item_parent_id($notification_data)
	{
		// Parent of an extension version is the extension itself:
		$id = substr(md5($notification_data['ext_name']), 0, 16);
		return intval(bcmod(self::bchex2dec($id), 10000000-1));
	}

	public function find_users_for_notification($notification_data, $options = array())
	{
		$options = array_merge(array(
			'ignore_users' => array(),
		), $options);

		//TODO: This may fail if this administrator permission is denied using "never"!
		$users = $this->auth->acl_get_list(false, $this->permission);
		$users = (!empty($users[0][$this->permission])? $users[0][$this->permission] : array());

		// Additionally, we want all founders, because apparently they are not in the list automatically:
		$sql = 'SELECT user_id FROM ' . USERS_TABLE . ' WHERE user_type = ' . USER_FOUNDER;
		$result = $this->db->sql_query($sql, 172790);
		$founders = $this->db->sql_fetchrowset($result);
		foreach ($founders as $user_ary)
		{
			$users[] = $user_ary['user_id'];
		}

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
		//TODO: This may not work if we are somewhere in a virtual subfolder (-> Mod Rewrite or similar)
		$board_url = generate_board_url() . '/';

		if ($this->get_data('security'))
		{
			return "<img class='avatar' alt='{$this->user->lang('UPDATE_AVAILABLE')}' src='${board_url}ext/gn36/versionchecknotifier/fixtures/{$this->notify_icon}_sec.png' />";
		}
		return "<img class='avatar' alt='{$this->user->lang('UPDATE_AVAILABLE')}' src='${board_url}ext/gn36/versionchecknotifier/fixtures/{$this->notify_icon}.png' />";
	}

	public function get_title()
	{
		$ext_name = $this->get_data('name');
		$version  = $this->get_data('version');
		$security = $this->get_data('security');

		return $this->user->lang($security ? $this->language_key_sec : $this->language_key, $ext_name, $version);
	}

	public function get_reason()
	{
		$ext_name = $this->get_data('name');
		$version  = $this->get_data('version');
		$old_version = $this->get_data('old_version');
		$dl = $this->get_data('download_url');

		if ($dl)
		{
			return $this->user->lang('REASON_UPDATE_DL', $ext_name, $version, $old_version, $dl);
		}

		return $this->user->lang('REASON_UPDATE', $ext_name, $version, $old_version);
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
		return append_sid("{$this->phpbb_root_path}ucp.{$this->php_ext}", array('i' => 'ucp_notifications', 'mode' => 'notification_list'));
	}

	public function get_redirect_url()
	{
		$nid = $this->notification_id;

		if ($nid !== null)
		{
			return append_sid("{$this->phpbb_root_path}app.{$this->php_ext}/versionchecknotifier/redirect/$nid");
		}
		// Revert back to the default and hope for the best if this doesn't work...
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
		$this->user->add_lang_ext('gn36/versionchecknotifier', 'mailing');
		// TODO: EMAIL_SIG
		return array(
			'EXTENSION' 	=> $this->get_data('name'),
			'NAME' 			=> $this->get_data('name'),
			'VERSION'		=> $this->get_data('version') ? $this->get_data('version') : $this->user->lang('NOT_AVAILABLE'),
			'OLD_VERSION' 	=> $this->get_data('old_version') ? $this->get_data('old_version') : $this->user->lang('NOT_AVAILABLE'),
			'SITENAME' 		=> $this->config['sitename'],
			'SECURITY'		=> $this->get_data('security') ? $this->user->lang('IS_SECURITY_UPDATE') : '',
			'U_INDEX' 		=> generate_board_url() . "/index.{$this->php_ext}",
			'DOWNLOAD'		=> $this->get_data('download_url') ? str_replace('&amp;', '&', $this->get_data('download_url')) : $this->user->lang('NOT_AVAILABLE'),
			'ANNOUNCEMENT' 	=> $this->get_data('announcement_url') ? str_replace('&amp;', '&', $this->get_data('announcement_url')) : $this->user->lang('NOT_AVAILABLE'),
			'ADD_INFO'		=> $this->get_data('text') ? $this->user->lang('ADDITIONAL_INFO', $this->get_data('text')) : '',
			//TODO: How to actually personalize this?
		);
	}

	protected function extract_version_info($notification_data)
	{
		// Lets start with "old version", that is a bit easier:
		if (isset($notification_data['old_version']) && is_array($notification_data['old_version']))
		{
			$old_ver = $notification_data['old_version']['version'];
		}
		else if (isset($notification_data['old_version']))
		{
			$old_ver = $notification_data['old_version'];
		}
		else
		{
			$old_ver = null;
		}
		$notification_data['old_version'] = $old_ver;

		// New version:
		if (isset($notification_data['version']) && is_array($notification_data['version']))
		{
			$elem = $notification_data['version'];
			foreach ($elem as $data)
			{
				// Skip all Versions that are too large
				if (phpbb_version_compare($old_ver, $data['current'], '>='))
				{
					#echo "Skipping: " . $old_ver . ' vs. ' . $data['current'] . " \n";
					continue;
				}

				if (isset($data['current']))
				{
					$notification_data['version'] = $data['current'];
				}
				if (isset($data['eol']) && (!isset($notification_data['eol']) || null === $notification_data['eol']))
				{
					$notification_data['eol'] = $data['eol'];
				}
				if (isset($data['security']) && (!isset($notification_data['security']) || null === $notification_data['security']))
				{
					$notification_data['security'] = $data['security'];
				}
				if (isset($data['announcement']) && (!isset($notification_data['announcement_url']) || !$notification_data['announcement_url']))
				{
					$notification_data['announcement_url'] = $data['announcement'];
				}
				if (isset($data['download']) && (!isset($notification_data['download_url']) || !$notification_data['download_url']))
				{
					$notification_data['download_url'] = $data['download'];
				}
			}
		}

		return $notification_data;
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

		// Extract version info if necessary:
		$notification_data = $this->extract_version_info($notification_data);

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

		if (isset($notification_data['eol']))
		{
			$this->set_data('eol', $notification_data['eol']);
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

		parent::create_insert_array($notification_data, $pre_create_data);
	}

	/**
	 * Update a notification
	 *
	 * @param array $notification_data Data specific for this type that will be updated
	 */
	public function update_notifications($notification_data)
	{
		$notification_data = $this->extract_version_info($notification_data);

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
