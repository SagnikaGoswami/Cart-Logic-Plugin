
<?php
/*
 * Plugin Name: Simple Cart Discount Plugin
 * Description: Applies a 15% discount to accessories when the cart subtotal reaches 2000.
 * Version: 1.0.1
 * Requires at least: 6.8.1
 * Requires PHP: 7.4
 * Author: Sagnika Goswami
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('woocommerce_cart_calculate_fees', 'clp_apply_discount');

function clp_apply_discount($cart) {
    if (is_admin() && !wp_doing_ajax()) {
        return;
    }

    if (!$cart || $cart->is_empty()) {
        return;
    }

    $accessories_total = 0;

    foreach ($cart->get_cart() as $cart_item) {
        $product_id = $cart_item['product_id'];

        if (has_term('accessories', 'product_cat', $product_id)) {
            $accessories_total += (float) $cart_item['line_subtotal'];
        }
    }

    if ($cart->get_subtotal() >= 2000 && $accessories_total > 0) {
        $discount = $accessories_total * 0.15;

        $cart->add_fee(
            '15% Accessories Discount',
            -$discount,
            false
        );
    }
}

add_action('woocommerce_before_cart', 'clp_show_discount_message');

function clp_show_discount_message() {
    if (!WC()->cart) {
        return;
    }

    foreach (WC()->cart->get_fees() as $fee) {
        if ($fee->name === '15% Accessories Discount') {
            wc_print_notice(
                'You have received a 15% Accessories Discount!',
                'success'
            );
            break;
        }
    }
}
