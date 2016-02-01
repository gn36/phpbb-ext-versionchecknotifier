<?php

/**
 *
 * @package gn36/versionchecknotifier
 * @copyright (c) 2015 Martin Beckmann
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace gn36\versionchecknotifier\cron;

//TODO
class versionchecknotifier extends \phpbb\cron\task\base
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\log\log_interface */
	protected $log;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/** @var \gn36\versionchecknotifier\helper\version_checker */
	protected $version_checker;

	/** @var int */
	protected $run_interval;

	public function __construct(\phpbb\cache\service $cache, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\log\log_interface $log, \phpbb\notification\manager $notification_manager, \gn36\versionchecknotifier\helper\version_checker $version_checker)
	{
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->log = $log;
		$this->version_checker = $version_checker;
		$this->notification_manager = $notification_manager;
		$this->run_interval = $config['versionchecknotifier_gc'];
	}

	/**
	 * Run this cronjob
	 * @see \phpbb\cron\task\task::run()
	 */
	public function run()
	{
		$now = time();

		//TODO
		$available_updates = $this->version_checker->check_ext_versions();

		$x = 0;
		foreach ($available_updates as $extname => $data)
		{
			$notify_data = array(
				'name' => $extname,
				'version' => $data['new'],
				'old_version' => $data['current'],
			);

			print_r($this->notification_manager->add_notifications('gn36.versionchecknotifier.notification.type.ext_update', $notify_data));
		}

		$this->config->set('versionchecknotifier_last_gc', $now, true);
	}

	/**
	 * Returns whether this cron job can run
	 * @see \phpbb\cron\task\base::is_runnable()
	 * @return bool
	 */
	public function is_runnable()
	{
		return isset($this->config['versionchecknotifier_last_gc']) && $this->config['versionchecknotifier_last_gc'] >= 0;
	}

	/**
	 * Should this cron job run now because enough time has passed since last run?
	 * @see \phpbb\cron\task\base::should_run()
	 * @return bool
	 */
	public function should_run()
	{
		$now = time();

		// Run at most every day
		return $now > $this->config['versionchecknotifier_last_gc'] + $this->run_interval;
	}

}
