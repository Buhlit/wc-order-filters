<?php namespace WoocommerceOrderRules\Inc\Models;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\Model\PluginModel;

class Bookings extends PluginModel {

    protected static $version = '1.0.0';
    protected static $tableName = 'bookings';
    protected static $primaryKey = 'id';
    protected static $columns = array(
        array(
            'name' => 'name',
            'type' => 'varchar(191)',
            'default' => 'NOT NULL'
        ),
        array(
            'name' => 'country',
            'type' => 'varchar(50)',
            'default' => 'NOT NULL'
        ),
        array(
            'name' => 'private_key',
            'type' => 'varchar(191)',
            'default' => 'NOT NULL'
        ),
        array(
            'name' => 'public_key',
            'type' => 'varchar(191)',
            'default' => 'NOT NULL'
        )
    );

    public function getName() {
        return $this->name;
    }

}