<?php

/**
*
* @package testing
* @copyright (c) 2016 gn#36
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gn36\hookup\tests\functional;

/**
* @group functional
*/
class install_test extends \phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return array('gn36/versionchecknotifier');
	}

	public function test_validate_posting()
	{
		// Check whether we can still call posting.php in edit mode (even though login prevents any useful use)
		$crawler = self::request('GET', 'posting.php?mode=edit&f=2&p=1');
		$this->assertContains('Username', $crawler->filter('dt')->text());
	}

	public function test_validate_ucp_settings()
	{
		$each_closure = function($node, $i)
		{
			return $node->text();
		};

		$this->login();
		$crawler = self::request('GET', 'ucp.php?i=ucp_notifications&mode=notification_options');
		$this->assertContains('A new version of an extension is available', implode(' ', $crawler->filter('td')->each($each_closure)));
		$this->assertContains('A new phpBB version is available', implode(' ', $crawler->filter('td')->each($each_closure)));

	}

	public function test_validate_viewtopic()
	{
		// Check whether we can still call viewtopic.php without errors
		$crawler = self::request('GET', 'viewtopic.php?f=2&p=1');
		$this->assertContains('Welcome', $crawler->filter('h2')->text());
	}
}
