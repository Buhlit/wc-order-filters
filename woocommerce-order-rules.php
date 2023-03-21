<?php namespace WoocommerceOrderRules;

use BuhlLib\PluginInit;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin Name: WooCommerce Order Rules
 * Description: Get an easy overview over all your orders by enhanced filters
 * Version: 1.0.0
 * Author: Buhl.dev
 * Author URI: https://buhl.dev
 * WC tested up to: 7.5.1
 */

if(!class_exists('BuhlLib\PluginInit')) {
    require_once 'lib/plugin-init.php';
}

new PluginInit(__NAMESPACE__, __FILE__);



