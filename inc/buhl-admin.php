<?php namespace WcOrderFilters\Inc;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\PluginAdmin;
use BuhlLib\Classes\PluginHook;
use WcOrderFilters\Inc\Models\OrderRules;

class BuhlAdmin extends PluginAdmin {

    protected function addActions()
    {
        parent::addActions();

        PluginHook::addAction('admin_head', $this, 'createTables');
        PluginHook::addAction('admin_enqueue_scripts', $this, 'addCustomCSS');
    }

    protected function addFilters() {
        parent::addFilters();
    }

    public function createTables()
    {
        OrderRules::updateTable();
    }

    public function addCustomCSS()
    {
        BuhlAdmin::addStyle('main');
    }

}