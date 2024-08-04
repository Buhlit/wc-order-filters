<?php defined('ABSPATH') || exit; ?>

<div class="wrap woocommerce-order-rules-wrapper">
    <h1 class="wp-heading-inline"><?php $BuhlAdmin::translateReplace('Edit order rule (ID: %s)', [$order_rule->getId()]); ?></h1>

    <form action="<?php echo admin_url('admin-post.php') ?>" method="post" id="post">
        <input type="hidden" name="action" value="update_order_rule">
        <input type="hidden" name="order_rule_id" value="<?php echo intval($order_rule->getId()); ?>">
        <?php wp_nonce_field('update-order-rule_' . get_current_user_id(), 'woocommerce-order-rules'); ?>
        <div id="poststuff">
            <div id="titlediv">
                <div id="titlewrap">
                    <input type="text" name="name" size="30" id="title" spellcheck="true" required
                           placeholder="<?php $BuhlAdmin::translate('Name') ?>"
                           value="<?php esc_attr_e($order_rule->getName()); ?>"
                           autocomplete="off">
                </div>
            </div>
        </div>

        <fieldset>
            <?php foreach (WC()->countries->continents as $continents) { ?>
                <legend><?php echo $continents['name']; ?></legend>
                <div>
                    <?php
                    foreach ($continents['countries'] as $country) { ?>
                        <span>
                        <input type="checkbox" id="<?php echo $country; ?>" name="countries[]"
                               value="<?php echo $country; ?>"
                                <?php echo in_array($country, $settings['countries']) ? 'checked' : ''; ?>
                        />
                        <label for="<?php echo $country; ?>"><?php echo WC()->countries->countries[$country]; ?></label>
                    </span>
                        <?php
                    } ?>
                </div>
                <?php
            } ?>
        </fieldset>

        <input class="button btn-primary" type="submit" value="<?php $BuhlAdmin::translate('Save'); ?>">
    </form>
</div>
