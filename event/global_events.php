<?php
/**
 *
 * @package gn36/versionchecknotifier
 * @copyright (c) 2015 Martin Beckmann
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */


namespace gn36\versionchecknotifier\event;

class global_events implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.permissions'			=> 'add_permissions',
		);
	}

	public function add_permissions($event)
	{
		// TODO
	}
}
