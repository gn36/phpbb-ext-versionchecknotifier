<?php
/**
 *
 * @package gn36/versionchecknotifier
 * @copyright (c) 2015 Martin Beckmann
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace gn36\versionchecknotifier\migrations;

class v_1_0_0_dev extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('versionchecknotifier_gc', 86400)),
			array('config.add', array('versionchecknotifier_last_gc', 0)),
			array('custom', array(array($this, 'notification_mails'))),
		);
	}

	public function revert_data()
	{
		return array(
			array('custom', array(array($this, 'remove_notifications'))),
		);
	}

	public function notification_mails()
	{
		// Insert a notification for each user out there
		$sqlary = array(
			"INSERT INTO " . USER_NOTIFICATIONS_TABLE . " (item_type, item_id, method, notify, user_id) SELECT 'gn36.versionchecknotifier.notification.type.ext_update', 0, 'notification.method.email', 1, user_id FROM " . USERS_TABLE . " WHERE user_type IN (0,3);",
			"INSERT INTO " . USER_NOTIFICATIONS_TABLE . " (item_type, item_id, method, notify, user_id) SELECT 'gn36.versionchecknotifier.notification.type.phpbb_update', 0, 'notification.method.email', 1, user_id FROM " . USERS_TABLE . " WHERE user_type IN (0,3);",
		);
		//$this->db->sql_return_on_error(true);
		foreach ($sqlary as $sql)
		{
			$this->db->sql_query($sql);
		}
		//$this->db->sql_return_on_error(false);
	}

	public function remove_notifications()
	{
		$sql = "DELETE FROM " . USER_NOTIFICATIONS_TABLE . " WHERE item_type " . $this->db->sql_like_expression('gn36.versionchecknotifier.' . $this->db->get_any_char());
		$this->db->sql_query($sql);
	}
}
