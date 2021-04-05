<?php namespace BuhlLib\Classes\Template;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\Debug\PluginDebug;
use BuhlLib\PluginInit;

class PluginTemplate
{

	public static function formatPath($path, $extension = 'php')
	{
		return str_replace('.', '/', $path) . '.' . $extension;
	}

	public static function displayTemplate($path, $parameters = array(), $echo = true)
	{
		$output = null;

		if(array_key_exists('BuhlAdmin', $parameters)) {
			$pluginPath = $parameters['BuhlAdmin']->getPluginRoot() . $path;
		} else if(array_key_exists('BuhlPublic', $parameters)) {
			$pluginPath = $parameters['BuhlPublic']->getPluginRoot() . $path;
		} else {
			$pluginPath = PluginInit::getPluginRoot($path);
		}

		$path = PluginTemplate::formatPath($pluginPath);

		if (file_exists($path)) {
			extract($parameters);

			ob_start();
			include $path;
			$output = ob_get_clean();
		} else {
			PluginDebug::writeToLog("Couldn't find the template ($path)");
		}

		if ($echo) {
			print $output;
		}

		return $output;
	}

}