<?php
/**
 * @package CustomComissions
 * @version 0.7.0
 * @author Sergii Riabokon
 */
/*
Plugin Name: Custom Comissions
Plugin URI: http://github.com/sergiiriabokon/customcomissions
Description: Adds custom comissions to the products price for WooCommerce
Author: Serge Riabokon
Version: 0.7.0
Author URI: http://sergiiriabokon.medium.com
*/

// prohibit direct execution
if ( !function_exists( 'register_post_type' ) ) {
	echo 'Plugin is not supposed to be called directly.';
	exit();
}

define( 'CC__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( CC__PLUGIN_DIR . 'class-cc-admin.php' );
require_once( CC__PLUGIN_DIR . 'class-cc-product-price.php' );


CustomComissions_Admin::init();
CustomComissions_Product_Price::init();
