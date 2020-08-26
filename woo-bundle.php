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
 * Add Custom JS
 */
function wcbp_add_custom_js() {

	global $post;

	if ( ! $post ) {
		return;
	}

	if ( 'product' == $post->post_type ) {
		?>

        <script type='text/javascript'>
            jQuery(document).ready(function () {
                //for Price tab
                jQuery('.product_data_tabs .general_tab').addClass('show_if_bundle_product').show();
                jQuery('#general_product_data .pricing').addClass('show_if_bundle_product').show();
            });
        </script>

		<?php
	}
}

add_filter( 'admin_footer', 'wcbp_add_custom_js' );


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


/**
 * Bundle Product Front-End
 */
function wcbp_bundle_product_frontend() {
	global $product;

	if ( 'bundle_product' == $product->get_type() ) {
		?>

        <form class="cart" method="post" enctype='multipart/form-data'>

            <h3>
				<?php
				$get_product_ids = get_post_meta( $product->get_id(), 'wcbp_bundle_product_id', true );

				if ( isset( $get_product_ids[0] ) ) {
					echo $get_product_ids;


				}

				?>
            </h3>

<?php woocommerce_quantity_input(
		array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		)
	); ?>

            <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"
                    class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
        </form>

		<?php
	}
}

add_action( 'woocommerce_single_product_summary', 'wcbp_bundle_product_frontend' );