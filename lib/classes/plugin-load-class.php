<?php namespace BuhlLib\Classes;

defined( 'ABSPATH' ) || exit;

class PluginLoadClass
{

	public static function formatPathToClass($path)
	{
		$path = self::reverseSlashes($path);
		$path = self::formatCharacters($path);
		return $path;
	}

	public static function formatCharacters($path)
	{
		$path = ucwords($path, '/-');
		return str_replace('-', '', $path);
	}

	public static function reverseSlashes($path)
	{
		return str_replace('/', '\\', $path);
	}

}