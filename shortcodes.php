<?php

add_shortcode('show_user_data', 'show_user_data_callback');

function show_user_data_callback()
{
    $user_id = get_current_user_id();
    $user_data = array(
        'current_billing_company' => get_user_meta($user_id, 'billing_company', true),
        'current_billing_address' => get_user_meta($user_id, 'billing_address_1', true),
        'current_billing_postcode' => get_user_meta($user_id, 'billing_postcode', true),
        'current_billing_city' => get_user_meta($user_id, 'billing_city', true),
        'current_billing_first_name' => get_user_meta($user_id, 'billing_first_name', true),
        'current_billing_last_name' => get_user_meta($user_id, 'billing_last_name', true),
        'current_billing_phone' => get_user_meta($user_id, 'billing_phone', true)
    );

    foreach($user_data as $key => $value)
    {
        echo $key . " - " . $value . '<br>';
    }

}