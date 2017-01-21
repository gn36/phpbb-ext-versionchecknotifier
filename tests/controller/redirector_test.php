<?php
/**
 *
 * @package testing
 * @copyright (c) 2016 gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace gn36\versionchecknotifier\tests\controller;

include dirname(__FILE__) . "/function_mocks.php";

class redirector_test extends \phpbb_test_case
{
	public function test_construct()
	{
		$inst = $this->get_instance();
		$this->assertInstanceOf('\gn36\versionchecknotifier\controller\redirector', $inst);
	}

	public function test_redirect()
	{
		// We will test exactly one case where get_url gets called:
		$inst = $this->get_instance(1);

		// Setup redirect mock:
		\gn36\versionchecknotifier\controller\redirect("", false, false, $this);

		// This should produce an error:
		try
		{
			$inst->handle(0);
			$this->fail("Failed to trigger error on 0");
		}
		catch (\Exception $msg)
		{
			$this->assertEquals("INVALID_NOTIFICATION_ID_REDIRECT", $msg->getMessage());
		}

		// This should also fail:
		try
		{
			$inst->handle(1);
			$this->fail("Failed to trigger error on 1");
		}
		catch (\Exception $msg)
		{
			$this->assertEquals("INVALID_NOTIFICATION_ID_REDIRECT", $msg->getMessage());
		}

		// This should redirect:
		try
		{
			$inst->handle(12345);
		}
		catch (\Exception $msg)
		{
			$this->fail("Redirect failed incorrectly on 12345: " . $msg->getMessage());
		}

		// Check the values
		$data = \gn36\versionchecknotifier\controller\redirect("", false, false, $this);
		$this->assertEquals(1, $data['callcount']);
		$this->assertEquals('urldummy', $data['data']['url']);
		$this->assertEquals(false, $data['data']['return']);
		$this->assertEquals(true, $data['data']['insecure']);

	}

	private function get_instance($get_url_count = 0, $registered = true)
	{
		$user = $this->getMockBuilder('\phpbb\user')
			->disableOriginalConstructor()
			->getMock();
		//$user = $this->getMock('\phpbb\user', array(), array('\phpbb\datetime'));
		$user->ip = '';
		$user->data = array(
			'user_id'		=> 2,
			'username'		=> 'user-name',
			'is_registered'	=> $registered,
			'user_colour'	=> '',
		);

		$manager = $this->getMockBuilder('\phpbb\notification\manager')
			->disableOriginalConstructor()
			->setMethods(array(
				'load_notifications'
			))
			->getMock();

		$notification = $this->getMockBuilder('\gn36\versionchecknotifier\notification\base')
			->disableOriginalConstructor()
			->setMethods(array(
				'get_url'
			))
			->getMock();

		$notification->expects($this->exactly($get_url_count))
			->method('get_url')
			->will($this->returnValue('urldummy'));

		// For some reasons, the return values of the manager don't work any longer.
		$manager->expects($this->any())
			->method('load_notifications')
			->will($this->returnValueMap(array(
				array('notification.method.board', array('notification_id' => 12345), array('notifications' => array(12345 => $notification))),
				array($this->anything(), array('notifications' => array("ERROR")))
			)));
		return new \gn36\versionchecknotifier\controller\redirector($user, $manager);
	}
}
