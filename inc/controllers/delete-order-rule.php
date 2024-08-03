<?php namespace WoocommerceOrderRules\Inc\Controllers;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\Controllers\PluginController;
use BuhlLib\Classes\PluginHook;
use WoocommerceOrderRules\Inc\Models\OrderRules;

class DeleteOrderRule extends PluginController {

    protected function addActions()
    {
        parent::addActions();

        PluginHook::addAction('admin_action_woocommerce-order-rules-delete', $this, 'deleteOrderRule');
    }

    public function deleteOrderRule()
    {
        $id = intval($_GET['id']);
        $action = sanitize_text_field($_GET['action']);

        // Verify the nonce with the dynamic value
        if (!wp_verify_nonce($_GET[$action . '_nonce'], $action . '_' . $id) ||
            !current_user_can('manage_woocommerce')) {
            wp_die('Verification failed');
        }

        $id = intval($_GET['id']);

        if(!empty($id)) {
            OrderRules::deleteByPrimaryKey($id);
        }

        wp_safe_redirect(wp_get_referer());
        exit;
    }


}