<?php

add_action( 'woocommerce_thankyou', 'put_all_new_orders_on_hold' );
function put_all_new_orders_on_hold( $order_id ) { 
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );
    $order->update_status( 'on-hold' );
}
