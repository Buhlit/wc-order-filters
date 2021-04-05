<?php namespace WoocommerceOrderRules\Inc\Controllers;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\AdminPage\PluginAdminPage;
use BuhlLib\Classes\Controllers\PluginController;
use BuhlLib\Classes\PluginHook;
use BuhlLib\Classes\Template\PluginListTable;
use WoocommerceOrderRules\Inc\BuhlAdmin;
use WoocommerceOrderRules\Inc\Models\Bookings;

class DisplayBookings extends PluginController {

    protected function addActions() {
        parent::addActions();

        PluginHook::addAction('admin_menu', $this, 'addAdminBookingPage');
    }

    public function addAdminBookingPage() {
        PluginAdminPage::addAdminPage('Boookings', 'bookings', 'activate_plugins', 'bookings', $this, 'test', 'dashicons-tickets-alt');
    }

    public function test() {
        $list = new PluginListTable('Alle Bookinger', 'bookings', Bookings::getAll(), array(
            'id' => array(
                'column' => 'ID',
                'default' => true,
                'orderby' => 'asc'
            ),
            'name' => array(
                'column' => 'Name'
            ),
            'country' => array(
                'column' => 'Country'
            ),
        ), array('id', 'name'));

        BuhlAdmin::getTemplate('bookings', array('table' => $list));
    }
}