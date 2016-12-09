<?php
/**
 *
 * @package gn36/versionchecknotifier
 * @copyright (c) 2016 Martin Beckmann
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace gn36\versionchecknotifier\tests\notification;

require_once dirname(__FILE__) . '/../../../../../../tests/notification/manager_helper.php';

abstract class base extends \phpbb_database_test_case
{
	protected $notifications, $db, $container, $user, $config, $auth, $cache;

	protected function get_notification_types()
	{
		return array(
			'gn36.versionchecknotifier.notification.type.ext_update',
			'gn36.versionchecknotifier.notification.type.phpbb_update',
		);
	}

	static protected function setup_extensions()
	{
		return array('gn36/versionchecknotifier');
	}

	protected function setUp()
	{
		parent::setUp();

		global $phpbb_root_path, $phpEx;

		global $db, $config, $user, $auth, $cache, $phpbb_container;

		$db = $this->db = $this->new_dbal();
		$config = $this->config = new \phpbb\config\config(array(
			//TODO
		));
		$lang = $this->lang = new \phpbb\language\language();
		$user = $this->user = new \phpbb\user($lang);
		$this->user_loader = new \phpbb\user_loader($this->db, $phpbb_root_path, $phpEx, 'phpbb_users');
		$auth = $this->auth = new \phpbb_mock_notifications_auth();
		$cache = $this->cache = new \phpbb\cache\service(
			new \phpbb\cache\driver\null(),
			$this->config,
			$this->db,
			$phpbb_root_path,
			$phpEx
		);

		$this->phpbb_dispatcher = new \phpbb_mock_event_dispatcher();

		$phpbb_container = $this->container = new \phpbb_mock_container_builder();

		$this->notifications = new \phpbb_notification_manager_helper(
			array(),
			array(),
			$this->container,
			$this->user_loader,
			$this->config,
			$this->phpbb_dispatcher,
			$this->db,
			$this->cache,
			$this->user,
			$phpbb_root_path,
			$phpEx,
			'phpbb_notification_types',
			'phpbb_notifications',
			'phpbb_user_notifications'
		);

		$phpbb_container->set('notification_manager', $this->notifications);

		$this->notifications->setDependencies($this->auth, $this->config);

		$types = array();
		foreach ($this->get_notification_types() as $type)
		{
			$type_parts = explode('.', $type);
			$class = $this->build_type('\gn36\versionchecknotifier\notification\\' . array_pop($type_parts));

			$types[$type] = $class;
			$this->container->set($type, $class);
		}

		$this->notifications->set_var('notification_types', $types);

		$this->db->sql_query('DELETE FROM phpbb_notification_types');
		$this->db->sql_query('DELETE FROM phpbb_notifications');
		$this->db->sql_query('DELETE FROM phpbb_user_notifications');
	}

	protected function build_type($type)
	{
		global $phpbb_root_path, $phpEx;

		return new $type($this->user_loader, $this->db, $this->cache->get_driver(), $this->user, $this->auth, $this->config, $phpbb_root_path, $phpEx, 'phpbb_notification_types', 'phpbb_notifications', 'phpbb_user_notifications');
	}
}
