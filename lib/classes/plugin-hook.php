<?php namespace BuhlLib\Classes;

defined( 'ABSPATH' ) || exit;

class PluginHook
{

	/**
	 * @reference: https://developer.wordpress.org/reference/functions/add_action/
	 *
	 * @param string $tag
	 * @param string $callback
	 * @param string $method
	 * @param int $priority
	 * @param int $accepted_args
	 */
	public static function addAction($tag, $callback, $method, $priority = 10, $accepted_args = 1)
	{
		add_action($tag, array($callback, $method), $priority, $accepted_args);
	}

	/**
	 * @reference: https://codex.wordpress.org/Function_Reference/remove_action
	 * remove_action() must be called inside a function and cannot be called directly in your plugin or theme.
	 *
	 * @param string $tag
	 * @param string $callback
	 * @param int $priority
	 */
	public static function removeAction($tag, $callback, $priority = 10)
	{
		remove_action($tag, $callback, $priority);
	}

	/**
	 * @reference: https://developer.wordpress.org/reference/functions/add_filter/
	 *
	 * @param string $tag
	 * @param string $callback
	 * @param string $method
	 * @param int $priority
	 * @param int $accepted_args
	 */
	public static function addFilter($tag, $callback, $method, $priority = 10, $accepted_args = 1)
	{
		add_filter($tag, array($callback, $method), $priority, $accepted_args);
	}

	/**
	 * @reference: https://codex.wordpress.org/Function_Reference/remove_filter
	 *
	 * @param string $tag
	 * @param string $callback
	 * @param int $priority
	 */
	public static function removeFilter($tag, $callback, $priority = 10)
	{
		remove_filter($tag, $callback, $priority);
	}

}