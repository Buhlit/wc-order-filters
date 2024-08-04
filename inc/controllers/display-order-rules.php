<?php namespace WoocommerceOrderRules\Inc\Controllers;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\AdminPage\PluginAdminPage;
use BuhlLib\Classes\Controllers\PluginController;
use BuhlLib\Classes\PluginHook;
use BuhlLib\Classes\Template\PluginListTable;
use WoocommerceOrderRules\Inc\BuhlAdmin;
use WoocommerceOrderRules\Inc\Models\OrderRules;

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
        PluginAdminPage::addAdminPage(BuhlAdmin::translate('Order filter rules', false), BuhlAdmin::translate('Order rules', false), 'manage_woocommerce', 'order-rules', $this, 'getOrderRulesTableList', 'dashicons-editor-ol');
        PluginAdminPage::addAdminSubPage('order-rules', BuhlAdmin::translate('Add new order filter rule', false), BuhlAdmin::translate('Add new', false), 'manage_woocommerce', 'add-order-rule', $this, 'addNewOrderRule');
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
            'title' => BuhlAdmin::translate('Add new order filter rule', false),
            'countries' => WC()->countries,
        ]);
    }
}