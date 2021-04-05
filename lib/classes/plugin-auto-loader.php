<?php namespace BuhlLib\Classes;

defined( 'ABSPATH' ) || exit;

use BuhlLib\PluginInit;

class PluginAutoLoader
{

	public static $plugin_namespace_path = [];
	public static $active_namespace = '';

	public static function autoLoader($class)
	{
		$file = explode('\\', $class);
		$namespace = $file[0];

		if (self::isBuhlPlugin($namespace) || $namespace == PluginInit::getLibNamespace()) {
			self::activateNamespace($namespace);

			$file_path = self::reverseBackslashes($class);
			$file_path = self::formatNamespace($namespace, $file_path);
			$file_path = self::addHyphenBetweenUpperCases($file_path);
			$file_path = self::toLower($file_path);
			$file_path = self::addPluginRootPath($file_path, $namespace);

			self::requireFile($file_path);
		}

	}

	public static function activateNamespace($namespace) {
		self::$active_namespace = $namespace;
	}

	public static function getActivePluginNamespace($with_ending_slash = true) {
		$namespace = self::$active_namespace;

		if ($with_ending_slash) {
			$namespace .= '/';
		}

		return $namespace;
	}

	public static function isBuhlPlugin($namespace) {
		return in_array($namespace, self::getPluginNamespace());
	}

	public static function requireFile($file_path)
	{
		if (file_exists($file_path . '.php')) {
			require $file_path . '.php';
		}
	}

	public static function addPluginRootPath($path, $namespace)
	{
		return self::getFileRoot($namespace) . $path;
	}

	public static function getFileRoot($namespace) {
		$key = array_search($namespace, self::getPluginNamespace());

		if($key !== false) {
			return $key;
		}

		return PluginInit::getPluginRoot();
	}

	public static function toLower($path)
	{
		return strtolower($path);
	}

	public static function addHyphenBetweenUpperCases($path)
	{
		return preg_replace('/\B([A-Z])/', '-$1', $path);
	}

	public static function formatNamespace($file, $path)
	{
		// In case of requiring lib files
		if ($file == PluginInit::getLibNamespace()) {
			$path = self::replaceNamespace(PluginInit::getLibNamespace(), $path, 'lib');
		} else {
			$path = self::replaceNamespace(self::getActivePluginNamespace(), $path);
		}

		return $path;
	}

	public static function replaceNamespace($namespace, $path, $replace = '')
	{
		return str_replace($namespace, $replace, $path);
	}

	public static function reverseBackslashes($class)
	{
		return str_replace('\\', '/', $class);
	}

	public static function setPluginNamespace($namespace)
	{
		self::$plugin_namespace_path[PluginInit::getPluginRoot()] = $namespace;
	}

	public static function getPluginNamespace()
	{
		return self::$plugin_namespace_path;
	}
}