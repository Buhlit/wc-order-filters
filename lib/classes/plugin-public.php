<?php namespace BuhlLib\Classes;

defined( 'ABSPATH' ) || exit;

class PluginPublic extends Plugin
{

	protected static $templatePath = 'public';


	protected function addActions()
	{
		parent::addActions();

		PluginHook::addAction('wp_enqueue_scripts', $this, 'enqueStyles');
	}

	public function enqueStyles($hook_prefix)
	{

	}
}