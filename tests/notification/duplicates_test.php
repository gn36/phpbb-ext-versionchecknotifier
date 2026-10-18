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

/**
 * The cron job runs periodically and hands the very same data to the notification manager every time.
 * Administrators must only be notified once per available version.
 */
class duplicates_test extends base
{
	protected function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/duplicates.xml');
	}

	protected function setUp(): void
	{
		parent::setUp();

		$board = new \phpbb\notification\method\board($this->user_loader, $this->db, $this->cache->get_driver(), $this->user, $this->config, 'phpbb_notification_types', 'phpbb_notifications');
		$this->container->set('notification.method.board', $board);
		$this->notifications->set_var('notification_methods', array('notification.method.board' => $board));
	}

	protected function count_notifications($type)
	{
		$sql = 'SELECT COUNT(*) AS num FROM phpbb_notifications n
			JOIN phpbb_notification_types nt ON n.notification_type_id = nt.notification_type_id
			WHERE nt.notification_type_name = \'' . $this->db->sql_escape($type) . '\'';
		$result = $this->db->sql_query($sql);
		$num = (int) $this->db->sql_fetchfield('num');
		$this->db->sql_freeresult($result);

		return $num;
	}

	/**
	 * Data as handed over by \gn36\versionchecknotifier\cron\versionchecknotifier
	 */
	protected function cron_data($new = '1.1.0')
	{
		return array(
			'name'			=> 'acme/demo',
			'old_version'	=> '1.0.0',
			'version'		=> array(
				'1.1' => array(
					'current'		=> $new,
					'announcement'	=> 'https://example.com/announcement',
					'download'		=> 'https://example.com/download',
					'eol'			=> null,
					'security'		=> false,
				),
			),
		);
	}

	public function test_same_update_is_only_notified_once()
	{
		$type = 'gn36.versionchecknotifier.notification.type.ext_update';

		$this->notifications->add_notifications($type, $this->cron_data());
		$first = $this->count_notifications($type);
		$this->assertGreaterThan(0, $first, 'Administrators should have been notified');

		// Next cron run: same data again
		$this->notifications->add_notifications($type, $this->cron_data());
		$this->notifications->add_notifications($type, $this->cron_data());
		$this->assertEquals($first, $this->count_notifications($type), 'The same update must not be notified again');
	}

	public function test_new_version_is_notified_again()
	{
		$type = 'gn36.versionchecknotifier.notification.type.ext_update';

		$this->notifications->add_notifications($type, $this->cron_data('1.1.0'));
		$first = $this->count_notifications($type);

		$this->notifications->add_notifications($type, $this->cron_data('1.1.1'));
		$this->assertEquals(2 * $first, $this->count_notifications($type), 'A newer version needs a new notification');
	}
}
