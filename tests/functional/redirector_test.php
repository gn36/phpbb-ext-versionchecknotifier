<?php
/**
 *
 * @package testing
 * @copyright (c) 2016 gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace gn36\versionchecknotifier\tests\functional;

/**
 * @group functional
 */
class redirector_test extends \phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return array('gn36/versionchecknotifier');
	}

	public function test_call_redirect()
	{
		$each_closure = function($node, $i)
		{
			return $node->text();
		};

		$this->login();

		$this->add_lang_ext('gn36/versionchecknotifier', 'global');

		$crawler = self::request('GET', 'app.php/versionchecknotifier/redirect/0?sid=' . $this->sid);
		$this->assertContains($this->lang('INVALID_NOTIFICATION_ID_REDIRECT'), implode(' ', $crawler->filter('p')->each($each_closure)));

		$crawler = self::request('GET', 'app.php/versionchecknotifier/redirect/1?sid=' . $this->sid);
		$this->assertContains($this->lang('INVALID_NOTIFICATION_ID_REDIRECT'), implode(' ', $crawler->filter('p')->each($each_closure)));

		// Put a notification into the db:
		// TODO: If tested with a dev version of phpBB 3.1, it should find the dev version of phpBB 3.2 as update :)
		$sql = 'SELECT user_id FROM ' . USERS_TABLE . ' WHERE username = \'admin\'';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->assertArrayHasKey('user_id', $row);
		$user_id = $row['user_id'];

		$sql = 'SELECT notification_type_id FROM ' . NOTIFICATION_TYPES_TABLE . ' WHERE notification_type_name = \'gn36.versionchecknotifier.notification.type.ext_update\'';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		if (!$row)
		{
			$sql = 'INSERT INTO ' . NOTIFICATION_TYPES_TABLE . $this->db->sql_build_array('INSERT', array(
				'notification_type_name' => 'gn36.versionchecknotifier.notification.type.ext_update',
				'notification_type_enabled' => 1
			));
			$result = $this->db->sql_query($sql);
			$type_id = $this->db->sql_nextid();
		}
		else
		{
			$type_id = $row['notification_type_id'];
		}
		$time = time();

		$sql_ary = array(
			'notification_type_id' => $type_id,
			'item_id' => 293657,
			'item_parent_id' => 3221273,
			'user_id' => $user_id,
			'notification_read' => 0,
			'notification_time' => $time,
			'notification_data' => 'a:6:{s:4:"name";s:12:"hjw/calendar";s:7:"version";s:5:"0.8.0";s:11:"old_version";s:5:"0.7.3";s:8:"security";b:0;s:12:"download_url";s:25:"viewtopic.php?f=2&amp;t=1";s:16:"announcement_url";s:25:"viewtopic.php?f=2&amp;t=1";}',
		);

		$sql = 'INSERT INTO ' . NOTIFICATIONS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
		$this->db->sql_query($sql);
		$last_id = $this->db->sql_nextid();
		$this->assertGreaterThan(0, $last_id);

		// Now check redirect
		$crawler = self::request('GET', 'app.php/versionchecknotifier/redirect/' . $last_id . '?sid=' . $this->sid);
		$this->assertNotContains($this->lang('INVALID_NOTIFICATION_ID_REDIRECT'), implode(' ', $crawler->filter('p')->each($each_closure)));
		$this->assertContains('example post', implode(' ', $crawler->filter('.content')->each($each_closure)));

		// Check for login window
		$this->logout();
		$crawler = self::request('GET', 'app.php/versionchecknotifier/redirect/' . $last_id . '?sid=' . $this->sid);
		$this->assertNotContains($this->lang('INVALID_NOTIFICATION_ID_REDIRECT'), implode(' ', $crawler->filter('p')->each($each_closure)));
		$this->assertContains($this->lang('LOGIN'), implode(' ', $crawler->filter('.content')->each($each_closure)));

	}
}
