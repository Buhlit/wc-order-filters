<?php namespace WoocommerceOrderRules\Inc\Controllers;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\Controllers\PluginController;
use BuhlLib\Classes\PluginHook;
use WoocommerceOrderRules\Inc\BuhlAdmin;
use WoocommerceOrderRules\Inc\Models\OrderRules;

class WcDisplayOrderRuleDropdown extends PluginController {

    protected function addActions() {
        parent::addActions();

        PluginHook::addAction('woocommerce_order_list_table_restrict_manage_orders', $this, 'addOrderRuleDropdown', 10, 2);
    }

    protected function addFilters()
    {
        parent::addFilters();

    }

    public function addOrderRuleDropdown($order_type, $which )
    {
        if($order_type === 'shop_order' && $which === 'top') {
            $order_rules = OrderRules::getAll();

            if(!empty($_GET['order_rule'])) {
                $current_order_rule = wc_clean($_GET['order_rule']);
            }

            if(!empty($order_rules)) {
                BuhlAdmin::getTemplate('display-order-rule-dropdown', ['order_rules' => $order_rules, 'current_order_rule' => $current_order_rule ?? '']);
            }
        }
    }

}