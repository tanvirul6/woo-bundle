<?php
/*
Plugin Name: WooCommerce Bundle Product
Description: A simple woocommerce bundle product plugin
Author: N/A
Version: 1.0
*/


/**
 * Register Product Type
 */
function wcbp_register_bundle_product_type() {
	class WC_Product_bundle_product extends WC_Product {
		public function __construct( $product ) {
			$this->product_type = 'bundle_product';
			parent::__construct( $product );
		}
	}
}
add_action('init', 'wcbp_register_bundle_product_type');


/**
 * Add Product Type Selector
 */
function wcbp_add_product_type_selector($type) {
	$type['bundle_product'] = 'Bundle product';

	return $type;
}
add_filter('product_type_selector','wcbp_add_product_type_selector');