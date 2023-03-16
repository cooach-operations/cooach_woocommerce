<?php

/*
This code adds a checkbox to the variations in woocommerce to indicate that it is tender based in it's pricing. Then we hook into the products
and add a contact us for pricing string to be shown on the frontend. The checkbox
makes it so that the variations is excluded from the FROM - TO text on the frontend.
*/

// Add custom field to variation options pricing form
add_action( 'woocommerce_variation_options_pricing', 'is_tender_based_variation', 10, 3 );
function is_tender_based_variation( $loop, $variation_data, $variation ) {
    woocommerce_wp_checkbox( array(
        'id'            => '_is_tender_based[' . $loop . ']',
        'wrapper_class' => 'form-row form-row-full',
        'label'         => __('Is Tender Based', 'woocommerce' ),
        'description'   => __('Check this box if the variation pricing should be tender based.', 'woocommerce' ),
        'value'         => get_post_meta( $variation->ID, '_is_tender_based', true ),
    ));
}

// Save custom field on product save
add_action( 'woocommerce_save_product_variation', 'my_custom_field_save_variations', 10, 2 );
add_action( 'woocommerce_admin_process_variation_object', 'my_custom_field_save_variations', 10, 2 );
function my_custom_field_save_variations( $variation_id, $i ) {
    if ( isset( $_POST['_is_tender_based'][ $i ] ) ) {
        update_post_meta( $variation_id, '_is_tender_based', 'yes' );
    } else {
        delete_post_meta( $variation_id, '_is_tender_based' );
    }
}
add_filter( 'woocommerce_variable_price_html', 'exclude_custom_field_from_price_range', 10, 2 );
function exclude_custom_field_from_price_range( $price, $product ) {
    if ( $product->is_type('variable') ) {
        // Get all variations for the product
        $variations = $product->get_available_variations();
        
        // Filter out variations with the custom field _my_custom_field set to yes
        $filtered_variations = array_filter( $variations, function( $variation ) {
            return ! get_post_meta( $variation['variation_id'], '_is_tender_based', true );
        });
        
        // Get the min and max prices for the filtered variations
        $min_price = $filtered_variations ? min( array_column( $filtered_variations, 'display_price' ) ) : '';
        $max_price = $filtered_variations ? max( array_column( $filtered_variations, 'display_price' ) ) : '';
        
        // If min and max prices are different, display the price range
        if ( $min_price !== $max_price ) {
            $price = sprintf( __( 'From %1$s to %2$s', 'woocommerce' ), wc_price( $min_price ), wc_price( $max_price ) );
        } else {
            $price = wc_price( $min_price );
        }

    }
    
    return $price;
}

// Hook into the 'woocommerce_get_price_html' filter
add_filter( 'woocommerce_get_price_html', 'custom_regular_price_html', 10, 2 );

function custom_regular_price_html( $price_html, $product ) {
    if (!$product->is_type('variable'))
    {
        if(get_post_meta($product->get_variation_id(), '_is_tender_based', true)) 
        {
            do_action('log_simple', $product->get_price(), $product->get_id(), $product->get_name());
            $price_html = "Contact us for pricing";
        }
    }
    return $price_html;
}

add_action('woocommerce_before_calculate_totals', 'recalculate_totals', 10, 1);

function recalculate_totals($cart_object)
{
    foreach($cart_object->get_cart() as $cart_item_key => $cart_item)
    {
        if(!$cart_item['data']->is_type('variable'))
        {
            $variation_id = $cart_item['data']->get_variation_id();
            if(get_post_meta($variation_id, '_is_tender_based', true))
            {
                $cart_item['data']->set_price(0);
            }
        }
    }
}
