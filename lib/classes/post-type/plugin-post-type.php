<?php namespace BuhlLib\Classes\PostType;

defined( 'ABSPATH' ) || exit;

/**
 * Reference: https://codex.wordpress.org/Function_Reference/register_post_type
 *
 * Class PluginPostType
 * @package BuhlLib\Classes
 */
class PluginPostType extends PluginRegisterPostType
{

	public function __construct()
	{
		parent::__construct();
	}

	public static function getPostById($id, $output = OBJECT, $filter = 'raw')
	{
		return get_post($id, $output, $filter);
	}

	public static function getPostByPostTitle($title, $output = OBJECT)
	{
		// TODO:: Fix deprecated function below - currently not using this file
        //return get_page_by_title($title, $output, self::$postType);
	}

	public static function getPosts($numberposts = 5, $category = 0, $include = array(), $exclude = array(), $suppress_filters = true)
	{
		return get_posts(array(
			'numberposts' => $numberposts,
			'category' => $category,
			'include' => $include,
			'exclude' => $exclude,
			'suppress_filters' => $suppress_filters,
			'post_type' => self::$postType
		));
	}
}
