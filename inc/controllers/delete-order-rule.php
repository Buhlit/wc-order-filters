<?php namespace WcOrderFilters\Inc\Controllers;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\Controllers\PluginController;
use BuhlLib\Classes\PluginHook;
use WcOrderFilters\Inc\Models\OrderRules;

class DeleteOrderRule extends PluginController {

    protected function addActions()
    {
        parent::addActions();

        PluginHook::addAction('admin_action_woocommerce-order-rules-delete', $this, 'deleteOrderRule');
    }

    public function deleteOrderRule()
    {
        $id = intval($_GET['id']);

        // Verify the nonce with the dynamic value
        if (!isset($_GET['wpnonce']) || !wp_verify_nonce($_GET['wpnonce'], 'woocommerce-order-rules-delete_nonce') ||
            !current_user_can('manage_woocommerce')) {
            wp_die('Verification failed');
        }

        if(!empty($id)) {
            OrderRules::deleteByPrimaryKey($id);
        }

        wp_safe_redirect(wp_get_referer());
        exit;
    }


}