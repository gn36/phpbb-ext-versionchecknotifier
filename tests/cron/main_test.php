<?php

/**
 *
 * @package testing
 * @copyright (c) 2015 gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace gn36\versionchecknotifier\tests\cron;

class main_test extends \phpbb_database_test_case
{
	static protected function setup_extensions()
	{
		return array('gn36/versionchecknotifier');
	}

	public function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/dataset.xml');
	}

	public function setUp() : void
	{
		parent::setUp();

		//$this->db = $this->new_dbal();

		$this->cache = $this->getMockBuilder('\phpbb\cache\service')->disableOriginalConstructor()->getMock();
		$this->log = $this->getMockBuilder('\phpbb\log\log')->disableOriginalConstructor()->getMock();
	}

	public function runProvider()
	{
		return array(
			'none' 	=> array(array(), array(), $this->never(), array($this->equalTo(array()))),
			'phpbb' => array(
				array(),
				array('phpbb' => array('new' => '2', 'current' => '1')),
				$this->once(),
				array(
					array(
						$this->equalTo('gn36.versionchecknotifier.notification.type.phpbb_update'),
						$this->equalTo(array('name' => 'phpbb', 'version' => '2', 'old_version' => '1'))
					)
				)),
			'ext' 	=> array(
				array('x/y' => array('new' => '2', 'current' => '1')),
				array(),
				$this->once(),
				array(
					array(
						$this->equalTo('gn36.versionchecknotifier.notification.type.ext_update'),
						$this->equalTo(array('name' => 'x/y', 'version' => '2', 'old_version' => '1')),
					),
				)),
			'ext2' 	=> array(
				array('x/y' => array('new' => '2', 'current' => '1'), 'a/b' => array('new' => '2', 'current' => '1')),
				array(),
				$this->exactly(2),
				array(
					array(
						$this->equalTo('gn36.versionchecknotifier.notification.type.ext_update'),
						$this->equalTo(array('name' => 'x/y', 'version' => '2', 'old_version' => '1'))
					),
					array(
						$this->equalTo('gn36.versionchecknotifier.notification.type.ext_update'),
						$this->equalTo(array('name' => 'a/b', 'version' => '2', 'old_version' => '1'))
					)
				)),
			'both' 	=> array(
				array('x/y' => array('new' => '2', 'current' => '1')),
				array('phpbb' => array('new' => '2', 'current' => '1')),
				$this->exactly(2),
				array(
					array(
						$this->equalTo('gn36.versionchecknotifier.notification.type.ext_update'),
						$this->equalTo(array('name' => 'x/y', 'version' => '2', 'old_version' => '1'))
					),
					array(
						$this->equalTo('gn36.versionchecknotifier.notification.type.phpbb_update'),
						$this->equalTo(array('name' => 'phpbb', 'version' => '2', 'old_version' => '1'))
					),
				)),
		);
	}

	public function test_construct()
	{
		$task = $this->get_task();
		$this->assertInstanceOf('\phpbb\cron\task\base', $task);
	}

	public function test_is_runnable()
	{
		$task = $this->get_task();
		$this->assertTrue($task->is_runnable());
	}

	public function test_should_run()
	{
		// 1: Has not run ever
		$task = $this->get_task();
		$this->assertTrue($task->should_run());

		// 2: Has just run
		$task = $this->get_task(time() - 1);
		$this->assertTrue(!$task->should_run());
	}

	/**
	 * @dataProvider runProvider
	 */
	public function test_run($ext_versions, $phpbb_versions, $expected_notifications, $expected_notification_data)
	{
		$task = $this->get_task(0, $ext_versions, $phpbb_versions, $expected_notifications, $expected_notification_data);
		$task->run();
	}

	private function get_task($last_run = 0, $ext_versions = array(), $phpbb_versions = array(), $expected_notifications = null, $expected_notification_data = null)
	{
		$db = $this->new_dbal();
		$this->db = $db;

		// When in doubt, allow anything:
		if ($expected_notifications === null)
		{
			$expected_notifications = $this->any();
			$expected_notification_data = $this->anything();
		}
		else if ($expected_notification_data === null)
		{
			$expected_notification_data = $this->anything();
		}

		$this->config = new \phpbb\config\config(array(
			'versionchecknotifier_last_gc' => $last_run,
			'versionchecknotifier_gc' => 86400,
		));

		$helper = $this->getMockBuilder('\gn36\versionchecknotifier\helper\version_checker')
			->disableOriginalConstructor()
			->setMethods(array(
				'check_ext_versions',
				'check_phpbb_version',
			))
			->getMock();
		$manager = $this->getMockBuilder('\phpbb\notification\manager')
			->disableOriginalConstructor()
			->setMethods(array(
				'add_notifications',
			))
			->getMock();

		$helper->expects($this->any())
			->method('check_ext_versions')
			->will($this->returnValue($ext_versions));
		$helper->expects($this->any())
			->method('check_phpbb_version')
			->will($this->returnValue($phpbb_versions));
		$call = $manager->expects($expected_notifications)
			->method('add_notifications');
		if (is_array($expected_notification_data))
		{
			call_user_func_array(array($call, 'with'), $expected_notification_data);
		}
		else
		{
			$call->with($expected_notification_data);
		}

		return new \gn36\versionchecknotifier\cron\versionchecknotifier($this->cache, $this->config, $db, $this->log, $manager, $helper);
	}
}
