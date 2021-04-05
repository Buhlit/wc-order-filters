<?php namespace BuhlLib\Classes\PostType;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\Plugin;
use BuhlLib\Classes\PluginHook;

class PluginRegisterPostType
{

	public static $postType;

	protected
		$name,
		$singular,
		$status = 'admin',
		$archive = false,
		$slug,
		$position = 80,
		$icon,
		$supports = array(),
		$args = array();

	public function __construct()
	{

		PluginHook::addAction('init', $this, 'registerPostType');
	}

	public function registerPostType()
	{
		$args = array(
			'labels' => array(
				'name' => Plugin::translate($this->getName(), false),
				'singular_name' => Plugin::translate($this->getSingular(), false)
			),
			'public' => $this->getPublic(),
			'has_archive' => $this->getArchive(),
			'rewrite' => array(
				'slug' => $this->getSlug()
			),
			'menu_icon' => $this->getIcon()
		);

		$args = array_merge($args, $this->getSupport(), $this->getArgs());

		register_post_type($this->getPostType(),
			$args
		);
	}

	protected function getPostType()
	{
		return self::$postType;
	}

	protected function setPostType($post_type)
	{
		self::$postType = $post_type;
	}

	protected function getSingular()
	{
		return $this->singular;
	}

	protected function setSingular($singular)
	{
		$this->singular = $singular;
	}

	protected function getName()
	{
		return $this->name;
	}

	protected function setName($name)
	{
		$this->name = $name;
	}

	protected function isPublic($status = false)
	{
		$this->status = $status;
	}

	protected function getPublic()
	{
		return $this->status;
	}

	protected function setArchive($archive = false)
	{
		$this->archive = $archive;
	}

	protected function getArchive()
	{
		return $this->archive;
	}

	protected function getSlug()
	{
		return $this->slug;
	}

	protected function setSlug($slug)
	{
		$this->slug = $slug;
	}

	protected function getArgs()
	{
		return $this->args;
	}

	protected function setArgs($args = array())
	{
		$this->args = $args;
	}

	protected function setSupport($supports = array())
	{
		$this->supports = array('supports' => $supports);
	}

	protected function getSupport()
	{
		return $this->supports;
	}

	protected function setIcon($icon)
	{
		$this->icon = $icon;
	}

	protected function getIcon()
	{
		return $this->icon;
	}

	protected function setPosition($position = 80)
	{
		return $this->position = $position;
	}

	protected function getPosition()
	{
		return $this->position;
	}
}