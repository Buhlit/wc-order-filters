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

        PluginHook::addFilter('woocommerce_order_list_table_prepare_items_query_args', $this, 'applyOrderRule');
    }

    public function applyOrderRule($query)
    {
        if(!empty($_GET['order_rule'])) {
            $order_rule_id = intval($_GET['order_rule']);

            if (!empty($order_rule_id)) {
                $order_rule = OrderRules::getByPrimaryKey($order_rule_id);

                if (!empty($order_rule)) {
                    $settings = unserialize($order_rule->settings);
                    $countries = $settings['countries'];

                    if (!empty($countries)) {
                        $query['billing_country'] = $countries;
                    }
                }
            }
        }

        return $query;
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