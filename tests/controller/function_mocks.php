<?php
/**
 *
 * @package testing
 * @copyright (c) 2016 gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace gn36\versionchecknotifier\controller;

function redirect($url, $return = false, $insecure = false, \phpbb_test_case $test_object = null, $reset_callcount = false)
{
	/** @var $test_obj_save \phpbb_test_case */
	static $test_obj_save = null;
	static $call_count = 0;
	static $data = array();

	if ($test_object !== null)
	{
		$test_obj_save = $test_object;
		if ($reset_callcount)
		{
			$call_count = 0;
		}

		return array(
			'data' => $data,
			'callcount' => $call_count
		);
	}

	$data['url'] = $url;
	$data['return'] = $return;
	$data['insecure'] = $insecure;

	// Check datatypes
	if ($test_obj_save !== null)
	{
		$test_obj_save->assertInternalType('string', $url);
		$test_obj_save->assertInternalType('bool', $return);
		$test_obj_save->assertInternalType('bool', $insecure);
	}
	else
	{
		throw new \Exception('Testobject not set, parameters cannot be checked.');
	}

	$call_count++;

}
