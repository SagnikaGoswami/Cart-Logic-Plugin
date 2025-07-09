<?php

/*
* Plugin Name: Simple Cart Discount Plugin
* Description: A simple plugin to add 15% discount on the accessories item if the subtotal is of minimum Rs. 2000.
* Version: 1.0.0
* Requires at least: 6.8.1
* Requires PHP: 7.4
* Author: Sagnika Goswami
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Checking condition for discount
add_action('woocommerce_cart_calculate_fees', 'clp_check_for_discount');

function clp_check_for_discount(){
    $conditon_for_accessories = false;

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
   $product = $cart_item['data'];
   $product_id = $cart_item['product_id'];
   $quantity = $cart_item['quantity'];
   $subtotal = WC()->cart->get_product_subtotal( $product, $quantity);

   if(has_term('Accessories', '', $product_id)){
    $conditon_for_accessories = true;
   }

   if($subtotal >= 2000 && $conditon_for_accessories){
    WC()->cart->add_fee('Discount on Accessories', -0.15 * $subtotal);
    WC()->session->set('clp_applied_discount', true);
   } else {
    WC()->session->set('clp_applied_discount', false);
   }
}
}

// Show Discount Message
add_action('woocommerce_before_cart', 'clp_show_discount_message');

function clp_show_discount_message(){
    $is_dicount_applied = WP()->session->get('clp_applied_discount');

    if($is_dicount_applied){
        echo '<div>You’ve received a 15% Accessories Discount!</div>';
    }
}


?>