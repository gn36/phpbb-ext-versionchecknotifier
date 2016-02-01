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
			'core.user_setup'		=> 'load_global_lang',
			'core.user_add_after'	=> 'notification_add',
		);
	}

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/** @var \phpbb\config\config */
	protected $config;

	public function __construct(\phpbb\user $user, \phpbb\notification\manager $notification_manager, \phpbb\config\config $config)
	{
		$this->user = $user;
		$this->notification_manager = $notification_manager;
		$this->config = $config;
	}

	public function notification_add($event)
	{
		if (!$this->config['email_enable'])
		{
			return;
		}

		$notifications_data = array(
			array(
				'item_type'	=> 'gn36.versionchecknotify.notification.type.ext_update',
				'method'	=> 'notification.method.email',
			),
			array(
				'item_type'	=> 'gn36.versionchecknotify.notification.type.phpbb_update',
				'method'	=> 'notification.method.email',
			),
		);

		foreach ($notifications_data as $subscription)
		{
			$this->notification_manager->add_subscription($subscription['item_type'], 0, $subscription['method'], $event['user_id']);
		}
	}

	public function load_global_lang($event)
	{
		//$this->user->add_lang_ext('gn36/versionchecknotifier', 'global');
		$lang_ary = $event['lang_set_ext'];
		$lang_ary[] = array(
			'ext_name' 	=> 'gn36/versionchecknotifier',
			'lang_set'	=> 'global',
		);
		$event['lang_set_ext'] = $lang_ary;
	}
}
