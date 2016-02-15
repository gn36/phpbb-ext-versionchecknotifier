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

	/** @var \Symfony\Component\DependencyInjection\ContainerInterface */
	protected $container;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/**
	 * Constructor
	 *
	 * @param \phpbb\extension\manager $manager
	 * @param \phpbb\version_helper $version_helper
	 * @param \phpbb\template\template $template
	 * @param \phpbb\config\config $config
	 */
	public function __construct(\phpbb\extension\manager $manager, \Symfony\Component\DependencyInjection\ContainerInterface $container, \phpbb\template\template $template, \phpbb\config\config $config)
	{
		$this->manager = $manager;
		$this->container = $container;
		$this->config = $config;
		$this->template = $template;
	}

	public function check_phpbb_version($force_update = false)
	{
		// Check phpBB Version:
		try
		{
			/** @var $version_helper \phpbb\version_helper */
			$version_helper = $this->container->get('version_helper');
			$new_versions 	= $version_helper->get_suggested_updates($force_update);
		}
		catch (\RuntimeException $e)
		{
			// Version check failed.
			// TODO: Maybe we should store the last successful check date somewhere
			// and notify the admin after a couple of unsuccessful tries?
			return false;
		}

		if (!$new_versions)
		{
			// No update necessary
			return array();
		}

		// Return the same format as for extensions:
		return array('phpbb' => array(
			'new' 		=> $new_versions,
			'current' 	=> $this->config['version'],
		));
	}

	/**
	 * Check versions for all extensions and return the ones that need an update
	 *
	 * @param bool $check_disabled
	 * @param bool $check_purged
	 * @param bool $force_update
	 * @return array extension names => version info(new, current)
	 */
	public function check_ext_versions($check_disabled = true, $check_purged = true, $force_update = false)
	{
		if ($check_disabled && $check_purged)
		{
			$extensions = $this->manager->all_available();
		}
		else if ($check_disabled)
		{
			$extensions = $this->manager->all_configured();
		}
		else if ($check_purged)
		{
			$extensions = array_diff($this->manager->all_available(), $this->manager->all_disabled());
		}
		else
		{
			$extensions = $this->manager->all_enabled();
		}

		$version_info = array();
		foreach (array_keys($extensions) as $extname)
		{
			try
			{
				$md_manager = $this->manager->create_extension_metadata_manager($extname, $this->template);

				// We only need an update if the version check returns potential updates
				if ($new_versions = $this->version_check($md_manager, $force_update))
				{
					$curr_version = $md_manager->get_metadata('version');

					$version_info[$extname] = array(
						'new' 		=> $new_versions,
						'current' 	=> $curr_version,
					);
				}
			}
			catch (\Exception $e)
			{
				// TODO: Should we store this information, if there is version check info available?
				continue;
			}
		}
		return $version_info;
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
	protected function version_check(\phpbb\extension\metadata_manager $md_manager, $force_update = false, $force_cache = false)
	{
		$meta = $md_manager->get_metadata('all');

		if (!isset($meta['extra']['version-check']))
		{
			//throw new \RuntimeException($this->user->lang('NO_VERSIONCHECK'), 1);
			// this is for cron, we want to ignore these cases
			// Return value is different from empty array still
			return false;
		}

		$version_check = $meta['extra']['version-check'];

		// Stupid scopes prevent this from being injected:
		/** @var $version_helper \phpbb\version_helper */
		$version_helper = $this->container->get('version_helper');
		$version_helper->set_current_version($meta['version']);
		$version_helper->set_file_location($version_check['host'], $version_check['directory'], $version_check['filename']);
		$version_helper->force_stability($this->config['extension_force_unstable'] ? 'unstable' : null);

		return $version_helper->get_suggested_updates($force_update, $force_cache);
	}
}
