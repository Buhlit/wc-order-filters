<?php namespace WoocommerceOrderRules\Inc\PostTypes;

defined( 'ABSPATH' ) || exit;

use BuhlLib\Classes\PostType\PluginPostType;

class Points extends PluginPostType {

    public function __construct()
    {
        $this->setPostType('points');
        $this->setSlug('points');
        $this->setName('Points');
        $this->setSingular('Point');
        $this->isPublic(true);
        $this->setArchive(true);
        $this->setPosition(10);
        $this->setIcon('dashicons-cart');
        $this->setSupport(array(
            'title', 'editor', 'thumbnail'
        ));
        $this->setArgs(array());
        parent::__construct();
    }

}