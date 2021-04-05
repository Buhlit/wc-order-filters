<?php namespace BuhlLib\Classes;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\Template\PluginTemplate;
use BuhlLib\PluginInit;

class Plugin
{

	protected static $templatePath = '';

	public function __construct()
	{
		$this->addActions();
		$this->addFilters();
	}

	/**
	 * Method used to add actions
	 */
	protected function addActions()
	{

	}

	/**
	 * Method used to add filters
	 */
	protected function addFilters()
	{

	}

	/**
	 * @param $string
	 * @param bool $echo
	 * @return mixed|string
	 */
	public static function translate($string, $echo = true)
	{

		if ($echo) {
			_e($string, PluginInit::getDomainName());
		} else {
			return __($string, PluginInit::getDomainName());
		}

		return $string;
	}

	public static function translateReplace($string, $values, $echo = true)
	{
		$string = static::translate($string, false);

		$string = vsprintf($string, $values);

		if ($echo) {
			echo $string;
		} else {
			return $string;
		}
	}


	public static function getTemplate($path, $parameters = array(), $echo = true)
	{
		$callingClass = get_called_class();

		if (PluginInit::getDomainName() . '\Inc\BuhlAdmin' == $callingClass) {
			$parameters['BuhlAdmin'] = PluginInit::getBuhlAdminClass();
		} elseif (PluginInit::getDomainName() . '\Inc\BuhlPublic' == $callingClass) {
			$parameters['BuhlPublic'] = PluginInit::getBuhlPublicClass();
		}

		$path = 'templates' . '.' . static::$templatePath . '.' . $path;

		return PluginTemplate::displayTemplate($path, $parameters, $echo);
	}

	public static function addStyle($path, $deps = false)
	{
		$handle = $path;
		$path = 'assets.css.' . static::$templatePath . '.' . $path;
		$path = PluginTemplate::formatPath($path, 'css');
		$path = PluginInit::getPluginUrl() . $path;

		wp_register_style($handle, $path, $deps, PluginInit::getVersion());
		wp_enqueue_style($handle);
	}

	public static function addScript($path, $deps = false)
	{
		$handle = $path;
		$path = 'assets.js.' . static::$templatePath . '.' . $path;
		$path = PluginTemplate::formatPath($path, 'js');
		$path = PluginInit::getPluginUrl() . $path;

		wp_register_script($handle, $path, $deps, PluginInit::getVersion());
		wp_enqueue_script($handle);
	}

	public static function getPluginRoot() {

		$key = array_search(strtok(get_called_class(), '\\'), PluginAutoLoader::getPluginNamespace());
		if($key !== false) {
			return $key;
		}

		return false;
	}

}