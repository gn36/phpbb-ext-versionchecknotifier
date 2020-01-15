<?php
/**
 *
 * @package gn36/versionchecknotifier
 * @copyright (c) 2016 Martin Beckmann
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace gn36\versionchecknotifier\controller;

class redirector
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\notification\manager */
	protected $manager;

	public function __construct(\phpbb\user $user, \phpbb\notification\manager $manager)
	{
		$this->user = $user;
		$this->manager = $manager;
	}

	public function handle($notify_id = 0)
	{
		$this->user->add_lang_ext('gn36/versionchecknotifier', 'global');
		if (!$notify_id)
		{
			trigger_error('INVALID_NOTIFICATION_ID_REDIRECT');
		}

		if (!$this->user->data['is_registered'])
		{
			login_box();
		}

		//$notifications = $this->manager->load_notifications(array('notification_id' => intval($notify_id)));
		$notifications = $this->manager->load_notifications('notification.method.board', array('notification_id' => intval($notify_id)));

		if (!isset($notifications['notifications'][$notify_id]))
		{
			trigger_error('INVALID_NOTIFICATION_ID_REDIRECT');
		}

		/** @var $notification \phpbb\notification\type\base */
		$notification = $notifications['notifications'][$notify_id];
		$url = $notification->get_url();

		redirect($url, false, true);
	}
}
