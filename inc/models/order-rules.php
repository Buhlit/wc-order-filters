<?php namespace WcOrderFilters\Inc\Models;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\Model\PluginModel;

class OrderRules extends PluginModel {

    protected static $version = '1.0.0';
    protected static $tableName = 'order_rules';
    protected static $primaryKey = 'id';
    protected static $columns = array(
        array(
            'name' => 'name',
            'type' => 'varchar(191)',
            'default' => 'NOT NULL'
        ),
        array(
            'name' => 'settings',
            'type' => 'LONGTEXT',
            'default' => 'NOT NULL'
        )
    );

    public function getName() {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

}