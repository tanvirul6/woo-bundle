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

add_action( 'init', 'wcbp_register_bundle_product_type' );


/**
 * Add Product Type Selector
 */
function wcbp_add_product_type_selector( $type ) {
	$type['bundle_product'] = 'Bundle product';

	return $type;
}

add_filter( 'product_type_selector', 'wcbp_add_product_type_selector' );


/**
 * Add Product Data Tabs
 */
function wcbp_add_product_data_tabs( $tabs ) {
	$tabs['bundle_product'] = array(
		'label'  => 'Bundle product',
		'target' => 'bundle_product_opt',
		'class'  => 'show_if_bundle_product'
	);

	return $tabs;
}

add_filter( 'woocommerce_product_data_tabs', 'wcbp_add_product_data_tabs' );


/**
 * Add Product Data Panels
 */
function wcbp_add_product_data_panels() {
	?>
    <div id='bundle_product_opt' class='panel woocommerce_options_panel'>
        <div class="options_group">
			<?php
			woocommerce_wp_text_input( array(
				'id'          => 'wcbp_bundle_product_id',
				'label'       => 'Product ID\'s',
				'placeholder' => '1,2,3',
				'desc_tip'    => 'true',
				'description' => 'Enter Product ID\'s Separate by comma(,).',
			) );
			?>
        </div>
    </div>
	<?php
}

add_action( 'woocommerce_product_data_panels', 'wcbp_add_product_data_panels' );


/**
 * Save Product Meta
 */
function wcbp_save_product_meta( $post_id ) {
	if ( isset( $_POST['wcbp_bundle_product_id'] ) ) {
		update_post_meta( $post_id, 'wcbp_bundle_product_id', sanitize_text_field( $_POST['wcbp_bundle_product_id'] ) );
	}
}

add_action( 'woocommerce_process_product_meta', 'wcbp_save_product_meta' );