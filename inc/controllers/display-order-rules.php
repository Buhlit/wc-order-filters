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

    public function addAdminOrderRulesPages() {
        PluginAdminPage::addAdminPage(BuhlAdmin::translate('Order filter rules', false), BuhlAdmin::translate('Order rules', false), 'manage_woocommerce', 'order-rules', $this, 'getOrderRulesTableList', 'dashicons-editor-ol');
        PluginAdminPage::addAdminSubPage('order-rules', BuhlAdmin::translate('Add new order filter rule', false), BuhlAdmin::translate('Add new', false), 'manage_woocommerce', 'add-order-rule', $this, 'addNewOrderRule');
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

    public function addNewOrderRule() {
        BuhlAdmin::getTemplate('add-new.display-new', ['title' => BuhlAdmin::translate('Add new order filter rule', false)]);
    }
}