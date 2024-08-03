<?php defined('ABSPATH') || exit; ?>

<div class="wrap woocommerce-order-rules-wrapper">
    <h1 class="wp-heading-inline"><?php esc_html_e($title); ?></h1>

    <form action="<?php echo admin_url('admin-post.php') ?>" method="post" id="post">
        <input type="hidden" name="action" value="add_new_order_rule">
        <?php wp_nonce_field( 'add-new-order-rule_' . get_current_user_id(), 'woocommerce-order-rules'); ?>
        <div id="poststuff">
            <div id="titlediv">
                <div id="titlewrap">
                    <input type="text" name="name" size="30" value="" id="title" spellcheck="true" required
                           placeholder="<?php $BuhlAdmin::translate('Name') ?>"
                           autocomplete="off">
                </div>
            </div>
        </div>

        <input class="button btn-primary" type="submit" value="<?php $BuhlAdmin::translate('Add new'); ?>">
    </form>
</div>

