<?php defined('ABSPATH') || exit; ?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e($table->getTitle()); ?></h1>

    <?php
    $BuhlAdmin::getTemplate('actions.add-new-order-rule');
    $table->display();
    ?>
</div>
