<?php namespace BuhlLib;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\PluginAutoLoader;
use BuhlLib\Classes\PluginLoadClass;

class PluginInit
{

	public static $BuhlAdmin;
	public static $BuhlPublic;
	protected static $pluginRoot;
	protected static $pluginDomain;
	protected static $pluginInfo;

	private $instantiate = array(
		'inc/controllers',
		'inc/post-types',
	);

	public function __construct($namespace, $plugin_root)
	{
		// Essential plugin info
		static::$pluginRoot = $plugin_root;
		$this->setDomainName($namespace);
		$this->instantiate = apply_filters('buhl_instantiate_classes', $this->instantiate);

		// Autoloader
		require_once 'classes/plugin-auto-loader.php';
		PluginAutoLoader::setPluginNamespace($namespace);
		spl_autoload_register(__NAMESPACE__ . '\classes\PluginAutoLoader::autoLoader');

		// Plugin File
		$this->activatePluginType();
		$this->autoInstantiate();
	}


	public static function getPluginInfo($key)
	{
		if (empty(self::$pluginInfo)) {
			self::$pluginInfo = get_plugin_data(static::getPluginRoot('index.php'));
		}

		return array_key_exists($key, self::$pluginInfo) ? self::$pluginInfo[$key] : false;
	}

	public static function getVersion()
	{
		return self::getPluginInfo('Version');
	}

	private function getInstantiate()
	{
		return $this->instantiate;
	}

	private function setDomainName($domain)
	{
		static::$pluginDomain = $domain;
	}

	public static function getDomainName()
	{
		return static::$pluginDomain;
	}

	public static function getPluginUrl($url = '')
	{
		return str_replace('lib/', '', plugin_dir_url(__FILE__)) . $url;
	}

	public static function getPluginRoot($path = '')
	{
		return str_replace(static::getBaseName(), '', self::$pluginRoot) . $path;
	}

	public static function getLibNamespace()
	{
		return __NAMESPACE__;
	}

	public static function getBaseName() {
		return basename(PluginInit::$pluginRoot);
	}

	private function autoInstantiate()
	{
		$pluginRoot = self::getPluginRoot();
		$pluginNamespaces = PluginAutoLoader::getPluginNamespace();
		$includes = $this->getInstantiate();
		$files = array();

		foreach ($includes AS $include) {
			$path = $pluginRoot . $include;
			$d_files = array_slice(scandir($path), 2);

			if (!empty($d_files)) {
				foreach ($d_files as $file) {
                    if($file === 'index.php') {
                        continue;
                    }

					$file_extension = '.' . pathinfo($path . $file, PATHINFO_EXTENSION);

					if ($file_extension == '.php') {
						$files[$pluginRoot][] = $include . '/' . basename($file, $file_extension);
					}
				}
			}
		}

		if (!empty($files)) {
			foreach ($files AS $pluginRoot => $_files) {
				foreach($_files AS $file) {
					$file = PluginLoadClass::formatPathToClass($file);
					$class = $pluginNamespaces[$pluginRoot] . '\\' . $file;

					new $class;
				}
			}
		}
	}

	private function activatePluginType()
	{

		if (is_admin()) {
			$class = static::$pluginDomain . '\\Inc\\'  . 'BuhlAdmin';
			static::$BuhlAdmin = new $class;
		} else {
			$class = static::$pluginDomain . '\\Inc\\'  . 'BuhlPublic';
			static::$BuhlPublic = new $class;
		}
	}

	public static function getBuhlAdminClass()
	{
		if (!is_object(static::$BuhlAdmin)) {
			static::$BuhlAdmin = new BuhlAdmin();
		}

		return static::$BuhlAdmin;
	}

	public static function getBuhlPublicClass()
	{
		if (!is_object(static::$BuhlPublic)) {
			static::$BuhlPublic = new BuhlPublic();
		}

		return static::$BuhlPublic;
	}

}