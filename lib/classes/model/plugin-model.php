<?php namespace BuhlLib\Classes\Model;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\Table\PluginTable;

class PluginModel extends PluginTable
{

	public function __construct($id = null, $values = array())
	{
		if (!is_null($id) && empty($values)) {
			$values = static::getByPrimaryKey($id, true);
		}

		$this->setValues($values);
	}

	private function setValues($values)
	{
		if (!empty($values)) {
			foreach ((array)$values AS $key => $value) {
				$this->{$key} = $value;
			}
		}
	}

	public static function insert($columns = array(), $formats = array())
	{
		global $wpdb;

		$wpdb->insert($wpdb->prefix . self::getTableName(), $columns, $formats);

		return self::getByPrimaryKey($wpdb->insert_id);
	}

	public static function updateBy($column, $match, $values)
	{
		global $wpdb;

		$results = $wpdb->update(static::getTableNameWithPrefix($wpdb), $values, $where = array($column => $match));

		if ($results === false) {
			return false;
		}

		return true;
	}

	public static function getByPrimaryKey($id, $wpdb_object = false)
	{
		global $wpdb;

		$table = $wpdb->prefix . self::getTableName();
		$row = $wpdb->get_row("SELECT * FROM $table WHERE " . self::getPrimaryKey() . " = " . $id);


		if (!$wpdb_object && $row !== null) {
			$main_class = get_called_class();
			$obj = new $main_class;

			foreach (self::getColumns() AS $column) {
				$obj->{$column['name']} = $row->{$column['name']};
			}
		}

		return isset($obj) ? $obj : $row;
	}

	public static function getBy($column, $value, $compare = '=', $prepare = '%s', $args = array())
	{
		global $wpdb;

		$table = self::getTableNameWithPrefix($wpdb);
		$where = self::formatWhere($args);
		$values = self::getValues($args, $value);
		$objs = array();


		if (!empty($values)) {
			$rows = $wpdb->get_results($wpdb->prepare(
				"SELECT *
            FROM $table WHERE $column $compare $prepare {$where}",
				$values
			));
		} else {
			$rows = $wpdb->get_results("SELECT * FROM $table WHERE $column $compare $prepare {$where}");
		}


		foreach ($rows AS $row) {
			$main_class = get_called_class();
			$obj = new $main_class($row->{self::getPrimaryKey()}, $row);
			$objs[] = $obj;
		}

		return $objs;
	}

	public static function getValues($args, $value)
	{
		$values = array();

		if (!empty($value)) {
			$values[] = $value;
		}

		if (!empty($args['where'])) {
			foreach ($args['where'] AS $arg) {
				if (is_array($arg)) {
					$values[] = $arg['value'];
				}
			}
		}

		return $values;
	}

	public static function formatWhere($args)
	{
		$where = '';
		$first_compare = 'AND';

		if (!empty($args['where'])) {
			foreach ($args['where'] AS $arg) {
				if (is_string($arg)) {
					$compare = $arg;
				}

				if (!is_string($arg)) {
					if (!isset($compare)) {
						$compare = 'AND';
					}

					if (!empty($arg['value']) && is_int($arg['value'])) {
						$type = '%d';
					} else {
						$type = '%s';
					}

					$_compare = isset($first_compare) ? $first_compare : $compare;
					$where .= ' ' . $_compare . ' ' . $arg['column'] . ' ' . $arg['compare'] . ' ' . $type;

					if (isset($first_compare)) {
						unset($first_compare);
					}
				}
			}

		}

		return $where;
	}

	public function getInstanceValues()
	{
		$values = array();
		$columns = self::getColumns(false);

		foreach ($columns AS $column) {
			$values[$column['name']] = $this->{$column['name']};
		}

		return $values;
	}

	/**
	 * Used to update the current instance with new values
	 *
	 * @return array|null|object
	 */
	public function update()
	{
		$values = $this->getInstanceValues();
		$_obj = static::updateBy(static::getPrimaryKey(), $this->{static::getPrimaryKey()}, $values);

		return $_obj;
	}

	/**
	 * Used to save the current instance to a new row in the DB
	 *
	 * @return array|null|object
	 */
	public function save()
	{
		$values = $this->getInstanceValues();
		$_obj = static::insert($values);

		return $_obj;
	}


	public function delete()
	{
		return self::deleteByPrimaryKey($this->{static::getPrimaryKey()});
	}

	public static function deleteBy($column, $value)
	{
		global $wpdb;

		if ($wpdb->delete(static::getTableNameWithPrefix($wpdb), array($column => $value)) !== false) {
			return true;
		}

		return false;
	}

	public static function deleteByPrimaryKey($id)
	{
		return static::deleteBy(static::getPrimaryKey(), $id);
	}

	public static function getAll()
	{
		global $wpdb;

		$table = self::getTableNameWithPrefix($wpdb);
		$objs = array();

		$rows = $wpdb->get_results("SELECT * FROM $table");

		foreach ($rows AS $row) {
			$main_class = get_called_class();
			$obj = new $main_class($row->{self::getPrimaryKey()}, $row);
			$objs[] = $obj;
		}

		return $objs;
	}

}