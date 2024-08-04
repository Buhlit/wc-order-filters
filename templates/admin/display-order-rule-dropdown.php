<select name="order_rule" id="filter-by-order-rule">
    <option value="0"><?php $BuhlAdmin::translate('No filter') ?></option>
    <?php foreach ($order_rules as $order_rule) { ?>
        <option <?php echo $current_order_rule === $order_rule->getId() ? 'selected' : ''; ?> value="<?php esc_attr_e($order_rule->getId()); ?>"><?php esc_html_e($order_rule->getName()); ?></option>
    <?php } ?>
</select>