<?php
/**
 * @package CustomComissions
 * @version 0.7.0
 * @author Sergii Riabokon
 */
/*
Plugin Name: Custom Comissions
Plugin URI: http://github.com/sergiiriabokon/customcomissions
Description: Adds custom commissions to the products price for WooCommerce
Author: Serge Riabokon
Version: 0.7.0
Author URI: http://sergiiriabokon.medium.com
*/

require_once( __DIR__ . '/class-cc-admin.php' );
require_once( __DIR__ . '/class-cc-product-price.php' );

CustomComissions_Admin::init();
CustomComissions_Product_Price::init();
