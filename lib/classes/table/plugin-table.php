<?php namespace BuhlLib\Classes\Table;

defined( 'ABSPATH' ) || exit;

class PluginTable
{
	protected static $version = '1.0.0';
	protected static $tableName = '';
	protected static $primaryKey = 'id';
	protected static $columns = array();

	public static function updateTable()
	{
		if (self::upgradeTable()) {
			self::createTable();
			self::updateStoredDbVersion();
		}
	}

	public static function updateStoredDbVersion()
	{
		update_option('buhl_' . self::getTableName() . '_id', self::getVersion());
	}

	public static function upgradeTable()
	{
		$db_version = get_option('buhl_' . self::getTableName() . '_id');

		if (version_compare(self::getVersion(), $db_version, '>')) {
			return true;
		}

		return false;
	}

	protected static function createTable()
	{
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$table = self::getTableNameWithPrefix($wpdb);
		$primaryKey = self::getPrimaryKey();
		$formatColumns = self::formatColumns();

		$sql = "CREATE TABLE $table (
          $primaryKey bigint(20) NOT NULL AUTO_INCREMENT,
          $formatColumns
          PRIMARY KEY  ($primaryKey)
        ) $charset_collate";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		return empty($wpdb->last_error);
	}

	protected static function formatColumns()
	{
		$columns = self::getColumns(false);
		$format_columns = '';
		$number_of_columns = count($columns);

		if (!empty($columns) && is_array($columns)) {
			foreach ($columns AS $key => $column) {
				$format_columns .= $column['name'] . ' ' . $column['type'] . ' ' . $column['default'] . ', ';

				if ($number_of_columns != $key + 1) {
					$format_columns .= PHP_EOL;
				}
			}
		}

		return $format_columns;
	}

	protected static function getColumns($include_primary_column = true)
	{
		$columns = static::$columns;

		if ($include_primary_column) {
			$columns[] = array('name' => static::getPrimaryKey());
		}

		return $columns;
	}

	protected static function getPrimaryKey()
	{
		return static::$primaryKey;
	}

	protected static function getTableName()
	{
		return static::$tableName;
	}

	protected static function getTableNameWithPrefix($wpdb)
	{
		return $wpdb->prefix . static::$tableName;
	}

	protected static function getVersion()
	{
		return static::$version;
	}

}