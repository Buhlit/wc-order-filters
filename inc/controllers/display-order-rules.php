<?php namespace WcOrderFilters\Inc\Controllers;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\AdminPage\PluginAdminPage;
use BuhlLib\Classes\Controllers\PluginController;
use BuhlLib\Classes\PluginHook;
use BuhlLib\Classes\Template\PluginListTable;
use WcOrderFilters\Inc\BuhlAdmin;
use WcOrderFilters\Inc\Models\OrderRules;

class DisplayOrderRules extends PluginController {

    protected function addActions() {
        parent::addActions();

        PluginHook::addAction('admin_menu', $this, 'addAdminOrderRulesPages');
    }

    protected function addFilters()
    {
        parent::addFilters();

        PluginHook::addFilter('order-rules_column_default', $this, 'parseOrderRulesSettingsValue', 10, 3);
    }

    public function parseOrderRulesSettingsValue($value, $item, $column_name)
    {

        if($column_name === 'settings') {
            $settings = unserialize($item[$column_name]);

            if(!empty($settings) && is_array($settings)) {
                if(!empty($settings['countries'])) {
                    $countries = WC()->countries->get_countries();
                    $value = '<b>Country:</b> ';
                    foreach($settings['countries'] as $iso_country) {
                        if(array_key_exists($iso_country, $countries)) {
                            $value .= $countries[$iso_country] . ', ';
                        }
                    }
                    $value = rtrim($value, ', ');
                }
            }
        }

        return $value;

    }

    public function addAdminOrderRulesPages() {
        PluginAdminPage::addAdminPage(BuhlAdmin::translate('Order filter rules', false), BuhlAdmin::translate('Order rules', false), 'manage_woocommerce', 'order-rules', $this, 'displayOrderRulesPage', 'dashicons-editor-ol');
        PluginAdminPage::addAdminSubPage('order-rules', BuhlAdmin::translate('Add new order filter rule', false), BuhlAdmin::translate('Add new', false), 'manage_woocommerce', 'add-order-rule', $this, 'addNewOrderRule');
    }

    public function displayOrderRulesPage()
    {
        if(!empty($_GET['id'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $this->getEditOrderRule();
        } else {
            $this->getOrderRulesTableList();
        }
    }

    public function getEditOrderRule()
    {
        $order_rule_id = intval($_GET['id']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

        $order_rule = OrderRules::getByPrimaryKey($order_rule_id);

        if(!empty($order_rule)) {
            $settings = unserialize($order_rule->settings);

            BuhlAdmin::getTemplate('edit.display-order-rule-edit', array('order_rule' => $order_rule, 'settings' => $settings));
        } else {
            $this->getOrderRulesTableList();
        }
    }

    public function getOrderRulesTableList() {
        $list = new PluginListTable(BuhlAdmin::translate('All Order Rules', false), 'order-rules', OrderRules::getAll(), array(
            'id' => array(
                'column' => 'ID',
                'default' => true,
                'orderby' => 'asc',
                'actions' => [
                    [
                        'label' => BuhlAdmin::translate('Edit', false),
                        'endpoint' =>  admin_url('admin.php') . '?page=order-rules&id=%d',
                        'specifier' => 'id',
                        'nonce' => true,
                        'nonce_action' => 'woocommerce-order-rules-delete_nonce',
                        'action_name' => 'view-order-rule'
                    ],
                    [
                        'label' => BuhlAdmin::translate('Delete', false),
                        'endpoint' =>  admin_url('admin.php') . '?action=woocommerce-order-rules-delete&id=%d&wpnonce=%s',
                        'specifier' => 'id',
                        'nonce' => true,
                        'nonce_action' => 'woocommerce-order-rules-delete_nonce',
                        'action_name' => 'delete-order-rule'
                    ],
                ]
            ),
            'name' => array(
                'column' => 'Name'
            ),
            'settings' => array(
                'column' => 'Settings'
            ),
        ), array('id', 'name'));

        BuhlAdmin::getTemplate('order-rules-list', array('table' => $list));
    }

    public function addNewOrderRule() {
        BuhlAdmin::getTemplate('add-new.display-new', [
            'title' => BuhlAdmin::translate('Add new order filter rule', false)
        ]);
    }
}