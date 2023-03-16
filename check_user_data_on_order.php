<?php

add_action('woocommerce_checkout_order_processed', 'check_user_data_on_order', 10, 0);
function check_user_data_on_order()
{
        $billing_company = WC()->checkout->get_value( 'billing_company' );
        $billing_address = WC()->checkout->get_value( 'billing_address_1' );
        $billing_postcode = WC()->checkout->get_value( 'billing_postcode' );
        $billing_city = WC()->checkout->get_value( 'billing_city' );
//        $billing_first_name = WC()->checkout->get_value( 'billing_first_name' );
//        $billing_last_name = WC()->checkout->get_value( 'billing_last_name' );
        $billing_phone = WC()->checkout->get_value( 'billing_phone' );
//        $billing_email = WC()->checkout->get_value( 'billing_email' );

    $attrs = array(
        'company_name' =>  $billing_company,
        'billing_address_1' => $billing_address,
        'billing_postcode' => $billing_postcode,
        'billing_city' => $billing_city,
        'billing_phone_number' => $billing_phone,
    );

    update_user_meta(get_current_user_id(), 'company_name', $billing_company);

    $onelogin_id = get_user_meta(get_current_user_id(), 'user_onelogin_id', true);
    $tol = new OneLoginHandler();
    $res = $tol->update_user($onelogin_id, $attrs);
}