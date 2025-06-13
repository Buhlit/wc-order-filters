<?php namespace BuhlLib\Classes\Debug;

defined( 'ABSPATH' ) || exit;

class PluginDebug
{

	public static function writeToLog($log)
	{
		if (is_array($log) || is_object($log)) {
			error_log(print_r($log, true));
		} else {
			error_log($log);
		}

	}

}