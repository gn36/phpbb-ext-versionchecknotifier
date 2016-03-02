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

class base_test extends base
{
	protected function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/notification.xml');
	}

	public function test_get_notification_type_id()
	{
		// Borrowed from the original test
		$ext_id = $this->notifications->get_notification_type_id('gn36.versionchecknotifier.notification.type.ext_update');
		$phpbb_id = $this->notifications->get_notification_type_id('gn36.versionchecknotifier.notification.type.phpbb_update');

		$this->assertEquals(array(
			'gn36.versionchecknotifier.notification.type.ext_update'	=> $ext_id,
			'gn36.versionchecknotifier.notification.type.phpbb_update'	=> $phpbb_id,
			),
			$this->notifications->get_notification_type_ids(array(
				'gn36.versionchecknotifier.notification.type.ext_update',
				'gn36.versionchecknotifier.notification.type.phpbb_update',
			))
		);
	}

	public function get_id_data()
	{
		return array(
			array('\gn36\versionchecknotifier\notification\base'),
			array('\gn36\versionchecknotifier\notification\ext_update'),
			array('\gn36\versionchecknotifier\notification\phpbb_update'),
		);
	}

	/**
	 * @dataProvider get_id_data
	 * @param string $type
	 */
	public function test_get_id($type)
	{
		$notification_data = array(
			'ext_name' 	=> 'asdf/jkla',
			'version'	=> '1.0.0',
		);

		/** @var $ext \gn36\versionchecknotifier\notification\base */
		$ext = $this->build_type($type);
		$this->assertEquals(5406942, $ext->get_item_id($notification_data));
		$this->assertEquals(678298,  $ext->get_item_parent_id($notification_data));

		// Make sure the result is different for a different version, but parent stays the same
		$notification_data = array(
			'ext_name' 	=> 'asdf/jkla',
			'version'	=> '1.0.1',
		);
		$this->assertEquals(116275, $ext->get_item_id($notification_data));
		$this->assertEquals(678298, $ext->get_item_parent_id($notification_data));

		// Make sure everything changes for a different extension:
		$notification_data = array(
			'ext_name' 	=> 'asdf/jklb',
			'version'	=> '1.0.0',
		);
		$this->assertEquals(9517528, $ext->get_item_id($notification_data));
		$this->assertEquals(903454,  $ext->get_item_parent_id($notification_data));
	}
}
