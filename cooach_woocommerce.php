<?php
/**
 * Plugin Name: Cooach - WooCommerce
 * Description: Helper functions and such for WooCommerce
 * Author: Stefan
 */

 if ( ! defined( 'ABSPATH' ) ) {
    exit;
 }

if ( !class_exists('Cooach_WooCommerce_Helper')) {
    class Cooach_WooCommerce_Helper {
        public function __construct() {
            define ( 'COOACH_WC_DIR', plugin_dir_path(__FILE__));
        }

        public function init() {
            require_once( COOACH_WC_DIR . 'shortcodes.php');
            require_once( COOACH_WC_DIR . 'tender_based_pricing.php');
            require_once( COOACH_WC_DIR . 'check_user_data_on_order.php');
            //require_once( COOACH_LOG_DIR . 'admin.php');
        }
    }
}

$cooach_woocommerce = new Cooach_WooCommerce_Helper();
$cooach_woocommerce->init();