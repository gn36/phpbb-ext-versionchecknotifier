<?php
/**
 *
 * @package testing
 * @copyright (c) 2016 gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace gn36\versionchecknotifier\tests\helper;


class version_checker_test extends \phpbb_test_case
{
	static protected function setup_extensions()
	{
		return array('gn36/versionchecknotifier');
	}

	public function test_construct()
	{
		$vc = $this->get_version_checker();
		$this->assertInstanceOf('\gn36\versionchecknotifier\helper\version_checker', $vc);
	}

	public function phpbb_test_data()
	{
		$vh_no_upd = array(
			'updates' => '',
			'set_current_version' => array(
				'calltimes' => $this->never(),
				'parameters' => $this->anything(),
			),
			'set_file_location' => array(
				'calltimes' => $this->never(),
				'parameters' => $this->anything(),
			),
			'force_stability' => array(
				'calltimes' => $this->never(),
				'parameters' => $this->anything(),
			)
		);

		$vh_upd = $vh_no_upd;
		$vh_upd['updates'] = '3.1.2';

		return array(
			'no_update' => array(
				'3.1.0', $vh_no_upd, array()
			),
			'new_version' => array(
				'3.1.1', $vh_upd, array('phpbb' => array('new' => $vh_upd['updates'], 'current' => '3.1.1')),
			)
		);
	}

	/**
	 * @dataProvider phpbb_test_data
	 */
	public function test_check_phpbb_version($phpbb_version, $vh, $expected)
	{
		// Default initialization: No update available:
		$vc = $this->get_version_checker($phpbb_version, $vh);
		$phpbb_updates = $vc->check_phpbb_version(false);
		$this->assertEquals($expected, $phpbb_updates);
		$phpbb_updates = $vc->check_phpbb_version(true);
		$this->assertEquals($expected, $phpbb_updates);
	}

	public function ext_test_data()
	{
		$version = '1.0.0';
		$host = 'example.com';
		$dir = 'example/dir/';
		$file = 'file.json';
		$stability = null;

		$md_tmp = array(
			'all' => array(
				'extra' => array(
					'version-check' => array(
						'host' => $host,
						'directory' => $dir,
						'filename' => $file,
					)
				),
				'version' => $version,
			),
			'version' => $version,
		);

		$mgr_enabled = array(
			'available' 	=> array('x/y' => './ext/x/y/'),
			'configured'	=> array('x/y' => array('ext_name' => 'x/y', 'ext_path' => './ext/x/y', 'ext_active' => 1)),
			'disabled'		=> array(),
			'enabled'		=> array('x/y' => array('ext_name' => 'x/y', 'ext_path' => './ext/x/y', 'ext_active' => 1)),
		);
		$mgr_disabled = array(
			'available' 	=> array('x/y' => './ext/x/y/'),
			'configured'	=> array('x/y' => array('ext_name' => 'x/y', 'ext_path' => './ext/x/y', 'ext_active' => 1)),
			'disabled'		=> array('x/y' => array('ext_name' => 'x/y', 'ext_path' => './ext/x/y', 'ext_active' => 1)),
			'enabled'		=> array(),
		);

		$vh_no_upd = array(
			'updates' => '',
			'set_current_version' => array(
				'calltimes' => $this->once(),
				'parameters' => $this->equalTo($version),
			),
			'set_file_location' => array(
				'calltimes' => $this->atLeastOnce(), // This should also be once, but oh well...
				'parameters' => array($this->equalTo($host), $this->equalTo($dir), $this->equalTo($file)),
			),
			'force_stability' => array(
				'calltimes' => $this->atLeastOnce(),
				'parameters' => $this->equalTo($stability),
			),
		);

		// TODO: Maybe there is a better way to force a complete copy of $this->once?
		$vh_no_upd1 = $vh_no_upd;
		$vh_no_upd1['set_current_version'] = array(
			'calltimes' => $this->once(),
			'parameters' => $this->equalTo($version),
		);
		$vh_no_upd2 = $vh_no_upd;
		$vh_no_upd2['set_current_version'] = array(
			'calltimes' => $this->once(),
			'parameters' => $this->equalTo($version),
		);
		$vh_no_upd3 = $vh_no_upd;
		$vh_no_upd3['set_current_version'] = array(
			'calltimes' => $this->once(),
			'parameters' => $this->equalTo($version),
		);
		$vh_no_upd4 = $vh_no_upd;
		$vh_no_upd4['set_current_version'] = array(
			'calltimes' => $this->once(),
			'parameters' => $this->equalTo($version),
		);

		$vh_upd = $vh_no_upd;
		$vh_upd['updates'] = '1.0.2';

		$vh_upd1 = $vh_upd;
		$vh_upd1['set_current_version'] = array(
			'calltimes' => $this->once(),
			'parameters' => $this->equalTo($version),
		);

		$vh_upd2 = $vh_upd;
		$vh_upd2['set_current_version'] = array(
			'calltimes' => $this->once(),
			'parameters' => $this->equalTo($version),
		);

		$vh_upd_dont_run = array(
			'updates' => '1.0.2',
			'set_current_version' => array(
				'calltimes' => $this->never(),
				'parameters' => $this->equalTo($version),
			),
			'set_file_location' => array(
				'calltimes' => $this->never(),
				'parameters' => array($this->equalTo($host), $this->equalTo($dir), $this->equalTo($file)),
			),
			'force_stability' => array(
				'calltimes' => $this->never(),
				'parameters' => $this->equalTo($stability),
			)
		);

		$vh_no_upd_unst = array(
			'updates' => '',
			'set_current_version' => array(
				'calltimes' => $this->atLeastOnce(),
				'parameters' => $this->equalTo($version),
			),
			'set_file_location' => array(
				'calltimes' => $this->atLeastOnce(),
				'parameters' => array($this->equalTo($host), $this->equalTo($dir), $this->equalTo($file)),
			),
			'force_stability' => array(
				'calltimes' => $this->atLeastOnce(),
				'parameters' => $this->equalTo('unstable'),
			)
		);

		$vh_upd_unst = $vh_no_upd_unst;
		$vh_upd_unst['updates'] = '1.0.2';

		return array(
			'no_update_enabled_stable' => array(
				'1.0.0', $vh_no_upd, $md_tmp, $mgr_enabled, $stability, true, true,
				array()
			),
			'no_update_enabled_stable_ft' => array(
				'1.0.0', $vh_no_upd1, $md_tmp, $mgr_enabled, $stability, false, true,
				array()
			),
			'no_update_enabled_stable_tf' => array(
				'1.0.0', $vh_no_upd2, $md_tmp, $mgr_enabled, $stability, true, false,
				array()
			),
			'no_update_enabled_stable_ff' => array(
				'1.0.0', $vh_no_upd3, $md_tmp, $mgr_enabled, $stability, false, false,
				array()
			),
			'no_update_disabled_stable' => array(
				'1.0.0', $vh_no_upd4, $md_tmp, $mgr_disabled, $stability, true, true,
				array()
			),
			'new_version_enabled_stable' => array(
				'1.0.1', $vh_upd, $md_tmp, $mgr_enabled, $stability, true, true,
				array('x/y' => array('new' => $vh_upd['updates'], 'current' => '1.0.0')),
			),
			'new_version_enabled_stable_tf' => array(
				'1.0.1', $vh_upd1, $md_tmp, $mgr_enabled, $stability, true, false,
				array('x/y' => array('new' => $vh_upd['updates'], 'current' => '1.0.0')),
			),
			'new_version_disabled_stable' => array(
				'1.0.1', $vh_upd2, $md_tmp, $mgr_disabled, $stability, true, true,
				array('x/y' => array('new' => $vh_upd['updates'], 'current' => '1.0.0')),
			),
			'new_version_disabled_stable_ff' => array(
				'1.0.1', $vh_upd_dont_run, $md_tmp, $mgr_disabled, $stability, false, false,
				array(),
			),
			'no_update_enabled_unstable' => array(
				'1.0.0', $vh_no_upd_unst, $md_tmp, $mgr_enabled, 'unstable', true, true,
				array()
			),
			'new_version_enabled_stable' => array(
				'1.0.1', $vh_upd_unst, $md_tmp, $mgr_enabled, 'unstable', true, true,
				array('x/y' => array('new' => $vh_upd['updates'], 'current' => '1.0.0')),
			),
		);
	}

	/**
	 * @dataProvider ext_test_data
	 * @param string $phpbb_version
	 * @param array $vh
	 * @param array $md
	 * @param array $mgr
	 * @param string|null $stability
	 * @param array $expected
	 */
	public function test_check_ext_versions($phpbb_version, $vh, $md, $mgr, $stability, $check_disabled, $check_purged, $expected)
	{
		$vc = $this->get_version_checker($phpbb_version, $vh, $md, $mgr, $stability);
		$result = $vc->check_ext_versions($check_disabled, $check_purged);
		$this->assertEquals($expected, $result);
	}

	private function get_version_checker($phpbb_version = '3.1.0', $vh = array(), $md = array(), $mgr = array(), $ext_stability = null)
	{
		$vh_tmp = array(
			'updates' => '',
			'set_current_version' => array(
				'calltimes' => $this->any(),
				'parameters' => $this->anything(),
			),
			'set_file_location' => array(
				'calltimes' => $this->any(),
				'parameters' => $this->anything(),
			),
			'force_stability' => array(
				'calltimes' => $this->any(),
				'parameters' => $this->anything(),
			)
		);

		$md_tmp = array(
			'all' => array(
				'extra' => array(
					'version-check' => array(
						'host' => 'example.com',
						'directory' => 'example/dir/',
						'filename' => 'file.json',
					)
				),
				'version' => '1.0.0',
			),
			'version' => '1.0.0',
		);

		$mgr_tmp = array(
			'available' 	=> array('x/y' => './ext/x/y/'),
			'configured'	=> array('x/y' => array('ext_name' => 'x/y', 'ext_path' => './ext/x/y', 'ext_active' => 1)),
			'disabled'		=> array(),
			'enabled'		=> array('x/y' => array('ext_name' => 'x/y', 'ext_path' => './ext/x/y', 'ext_active' => 1)),
		);

		$vh = array_merge($vh_tmp, $vh);
		$md = array_merge($md_tmp, $md);
		$mgr = array_merge($mgr_tmp, $mgr);

		$this->config = new \phpbb\config\config(array(
			'version' => $phpbb_version,
			'extension_force_unstable' => $ext_stability,
		));

		$manager = $this->getMockBuilder('\phpbb\extension\manager')
			->disableOriginalConstructor()
			->setMethods(array(
				'all_available',
				'all_configured',
				'all_disabled',
				'all_enabled',
				'create_extension_metadata_manager',
			))
			->getMock();

		$md_manager = $this->getMockBuilder('\phpbb\extension\metadata_manager')
			->disableOriginalConstructor()
			->setMethods(array(
				'get_metadata'
			))
			->getMock();

		$md_manager->expects($this->any())
			->method('get_metadata')
			->will($this->returnValueMap(array(
				array('all', $md['all']),
				array('version', $md['version']),
			)));
		$manager->expects($this->any())
			->method('all_available')
			->will($this->returnValue($mgr['available']));
		$manager->expects($this->any())
			->method('all_configured')
			->will($this->returnValue($mgr['configured']));
		$manager->expects($this->any())
			->method('all_disabled')
			->will($this->returnValue($mgr['disabled']));
		$manager->expects($this->any())
			->method('all_enabled')
			->will($this->returnValue($mgr['enabled']));
		$manager->expects($this->any())
			->method('create_extension_metadata_manager')
			->will($this->returnValue($md_manager));

		$container = $this->getMockBuilder('\Symfony\Component\DependencyInjection\Container')
			->disableOriginalConstructor()
			->setMethods(array(
				'get'
			))
			->getMock();
		// We need to clone to reset the counters
		$version_helper = $this->getMockBuilder('\phpbb\version_helper')
			->disableOriginalConstructor()
			->setMethods(array(
				'get_suggested_updates',
				'set_current_version',
				'set_file_location',
				'force_stability'
			))
			->getMock();
		$version_helper->expects($this->any())
			->method('get_suggested_updates')
			->will($this->returnValue($vh['updates']));
		$version_helper->expects($vh['set_current_version']['calltimes'])
			->method('set_current_version')
			->with($vh['set_current_version']['parameters']);
		$call = $version_helper->expects($vh['set_file_location']['calltimes'])
			->method('set_file_location');
		if (is_array($vh['set_file_location']['parameters']))
		{
			call_user_func_array(array($call, 'with'), $vh['set_file_location']['parameters']);
		}
		else
		{
			$call->with($vh['set_file_location']['parameters']);
		}
		$version_helper->expects($vh['force_stability']['calltimes'])
			->method('force_stability')
			->with($vh['force_stability']['parameters']);
		$template = $this->getMockBuilder('\phpbb\template\template')
			->disableOriginalConstructor()
			->disableProxyingToOriginalMethods()
			->getMock();
		$container->expects($this->any())
			->method('get')
			->will($this->returnValue($version_helper));
		return new \gn36\versionchecknotifier\helper\version_checker($manager, $container, $template, $this->config);
	}
}
