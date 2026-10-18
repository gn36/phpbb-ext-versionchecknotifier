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
	protected $notifications, $language, $db, $container, $user, $config, $auth, $cache;

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

	protected function setUp(): void
	{
		parent::setUp();

		global $phpbb_root_path, $phpEx;

		global $db, $config, $user, $auth, $cache, $phpbb_container;

		$db = $this->db = $this->new_dbal();
		$config = $this->config = new \phpbb\config\config(array(
			'allow_board_notifications' => true,
		));
		$lang_loader = new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx);
		$this->language = new \phpbb\language\language($lang_loader);
		$user = $this->user = new \phpbb\user($this->language, '\phpbb\datetime');
		$user->data['user_id'] = 0;
		$user->data['user_type'] = USER_NORMAL;
		$this->user_loader = new \phpbb\user_loader($this->db, $phpbb_root_path, $phpEx, 'phpbb_users');
		$auth = $this->auth = new \phpbb_mock_notifications_auth();
		$this->phpbb_dispatcher = new \phpbb_mock_event_dispatcher();
		$cache_driver = new \phpbb\cache\driver\dummy();
		$cache = $this->cache = new \phpbb\cache\service(
			$cache_driver,
			$this->config,
			$this->db,
			$this->phpbb_dispatcher,
			$phpbb_root_path,
			$phpEx
		);

		$phpbb_container = $this->container = new \phpbb_mock_container_builder();

		$this->notifications = new \phpbb_notification_manager_helper(
			array(),
			array(),
			$this->container,
			$this->user_loader,
			$this->phpbb_dispatcher,
			$this->db,
			$this->cache,
			$this->language,
			$this->user,
			'phpbb_notification_types',
			'phpbb_user_notifications'
		);

		$phpbb_container->set('notification_manager', $this->notifications);

		$this->notifications->setDependencies($this->auth, $this->config);

		$types = array();
		foreach ($this->get_notification_types() as $type)
		{
			$type_parts = explode('.', $type);
			$type_parts_last = array_pop($type_parts);
			$class = $this->build_type('\\gn36\\versionchecknotifier\\notification\\' . $type_parts_last);

			$types[$type] = $class;
			// Like "shared: false" in services.yml: every request for the type yields a new instance
			$class_name = '\\gn36\\versionchecknotifier\\notification\\' . $type_parts_last;
			$this->container->set($type, array(function () use ($class_name) {
				return $this->build_type($class_name);
			}, array()));
		}

		$this->notifications->set_var('notification_types', $types);

		$this->db->sql_query('DELETE FROM phpbb_notification_types');
		$this->db->sql_query('DELETE FROM phpbb_notifications');
		$this->db->sql_query('DELETE FROM phpbb_user_notifications');
	}

	protected function build_type($type)
	{
		global $phpbb_root_path, $phpEx;

		/** @var \gn36\versionchecknotifier\notification\base $instance */
		$instance = new $type($this->db, $this->language, $this->user, $this->auth, $phpbb_root_path, $phpEx, 'phpbb_user_notifications');
		$instance->set_config($this->config);
		$instance->set_user_loader($this->user_loader);

		return $instance;
	}
}
