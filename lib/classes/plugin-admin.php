<?php namespace BuhlLib\Classes;

defined( 'ABSPATH' ) || exit;

class PluginAdmin extends Plugin
{

	protected static $templatePath = 'admin';


	protected function addActions()
	{
		parent::addActions();

		PluginHook::addAction('admin_enqueue_scripts', $this, 'enqueAdminStyles');
	}

	public function enqueAdminStyles($hook_prefix)
	{

	}

}