<?php namespace BuhlLib\Classes\AdminPage;

defined( 'ABSPATH' ) || exit;

class PluginAdminPage
{

	public static function addAdminPage($page_title, $menu_title, $capability, $menu_slug, $obj, $callback = '', $icon_url = '', $position = null)
	{
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($obj, $callback), $icon_url, $position);
	}

	public static function addAdminSubPage($parent, $page_title, $menu_title, $capability, $menu_slug, $obj, $callback = '')
	{
		add_submenu_page($parent, $page_title, $menu_title, $capability, $menu_slug, array($obj, $callback));
	}
}

