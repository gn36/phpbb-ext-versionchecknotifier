<?php
/**
 *
 * @package gn36/versionchecknotifier
 * @copyright (c) 2015 Martin Beckmann
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace gn36\versionchecknotifier\helper;


class version_checker
{
	/** @var \phpbb\extension\manager */
	protected $manager;

	/** @var \phpbb\extension\metadata_manager */
	protected $md_manager = null;

	/** @var \phpbb\version_helper */
	protected $version_helper;

	/** @var \phpbb\user */
	protected $user;

	public function __construct(\phpbb\extension\manager $manager, \phpbb\version_helper $version_helper, \phpbb\user $user)
	{
		$this->manager = $manager;
		$this->version_helper = $version_helper;
		$this->user = $user;
	}

	/**
	 * Check the version and return the available updates (copied & modified from acp_extensions)
	 *
	 * @param \phpbb\extension\metadata_manager $md_manager The metadata manager for the version to check.
	 * @param bool $force_update Ignores cached data. Defaults to false.
	 * @param bool $force_cache Force the use of the cache. Override $force_update.
	 * @return string
	 * @throws RuntimeException
	 */
	public function version_check(\phpbb\extension\metadata_manager $md_manager, $force_update = false, $force_cache = false)
	{
		$meta = $md_manager->get_metadata('all');

		if (!isset($meta['extra']['version-check']))
		{
			throw new \RuntimeException($this->user->lang('NO_VERSIONCHECK'), 1);
		}

		$version_check = $meta['extra']['version-check'];

		$version_helper = $this->version_helper;
		$version_helper->set_current_version($meta['version']);
		$version_helper->set_file_location($version_check['host'], $version_check['directory'], $version_check['filename']);
		$version_helper->force_stability($this->config['extension_force_unstable'] ? 'unstable' : null);

		return $updates = $version_helper->get_suggested_updates($force_update, $force_cache);
	}
}