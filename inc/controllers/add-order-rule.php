<?php namespace WoocommerceOrderRules\Inc\Controllers;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\Controllers\PluginController;
use BuhlLib\Classes\PluginHook;
use WoocommerceOrderRules\Inc\Models\OrderRules;

class addOrderRule extends PluginController {

    protected function addActions()
    {
        parent::addActions();

        PluginHook::addAction('admin_post_add_new_order_rule', $this, 'validateAddingNewOrderRule');
    }

    public function validateAddingNewOrderRule()
    {

        // Verify the nonce with the dynamic value
        if (!wp_verify_nonce($_POST['woocommerce-order-rules'], 'add-new-order-rule_' . get_current_user_id()) ||
            !current_user_can('manage_woocommerce')) {
            wp_die('Verification failed');
        }

        $name = sanitize_text_field($_POST['name']);
        $raw_countries = $_POST['countries'];
        $countries = [];
        foreach($raw_countries AS $country) {
            $countries[] = sanitize_text_field($country);
        }

        $rule = $this->addNewOrderRule($name, [
            'countries' => $countries,
        ]);

        if(is_a($rule, 'WoocommerceOrderRules\Inc\Models\OrderRules')) {
            wp_safe_redirect( admin_url('admin.php?page=order-rules') );
            exit();
        }

        // TODO:: Error handling
        wp_safe_redirect( wp_get_referer() );
        exit();
    }

    public function addNewOrderRule($name, $settings)
    {

        if(!empty($name)) {
            return OrderRules::insert([
                'name' => $name,
                'settings' => serialize($settings)
            ]);
        }

        return false;
    }

}