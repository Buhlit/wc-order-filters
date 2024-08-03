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

        PluginHook::addAction('admin_menu', $this, 'addAdminBookingPage');
    }

    public function addAdminBookingPage() {
        PluginAdminPage::addAdminPage('Order filter rules', 'Order filter rules', 'activate_plugins', 'order-rules', $this, 'getOrderRulesTableList', 'dashicons-editor-ol');
    }

    public function getOrderRulesTableList() {
        $list = new PluginListTable('All Order Rules', 'order-rules', OrderRules::getAll(), array(
            'id' => array(
                'column' => 'ID',
                'default' => true,
                'orderby' => 'asc'
            ),
            'name' => array(
                'column' => 'Name'
            ),
            'settings' => array(
                'column' => 'settings'
            ),
        ), array('id', 'name'));

        BuhlAdmin::getTemplate('order-rules-list', array('table' => $list));
    }
}