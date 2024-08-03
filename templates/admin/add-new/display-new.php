<?php defined('ABSPATH') || exit; ?>

<div class="wrap woocommerce-order-rules-wrapper">
    <h1 class="wp-heading-inline"><?php esc_html_e($title); ?></h1>

    <form action="" name="post" method="post" id="post">
        <!-- TODO:: add nonce -->
        <div id="poststuff">
            <div id="titlediv">
                <div id="titlewrap">
                    <label id="title-prompt-text" for="title"><?php $BuhlAdmin::translate('Name'); ?></label>
                    <input type="text" name="title" size="30" value="" id="title" spellcheck="true"
                           autocomplete="off">
                </div>
            </div>
        </div>

        <input class="button btn-primary" type="submit" value="<?php $BuhlAdmin::translate('Add new'); ?>">
    </form>
</div>

