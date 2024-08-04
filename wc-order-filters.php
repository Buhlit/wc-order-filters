<?php namespace WcOrderFilters;

use BuhlLib\PluginInit;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin Name: Order filters for WooCommerce
 * Description: Get an easy overview over all your orders by enhanced filters
 * Requires Plugins: woocommerce
 * Version: 1.0.0
 * Author: Buhl.dev
 * Author URI: https://buhl.dev
 * WC tested up to: 9.1.4
 */

// Test to see if WooCommerce is active (including network activated).
$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';

if (
    in_array( $plugin_path, wp_get_active_and_valid_plugins() )
    || in_array( $plugin_path, wp_get_active_network_plugins() )
) {

    if (!class_exists('BuhlLib\PluginInit')) {
        require_once 'lib/plugin-init.php';
    }

    add_action('before_woocommerce_init', function () {
        if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        }
    });

    new PluginInit(__NAMESPACE__, __FILE__);
}



