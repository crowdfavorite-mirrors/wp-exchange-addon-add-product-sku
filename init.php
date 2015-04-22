<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	// Register's the metabox for any product type that supports the feature
	function it_exchange_add_product_sku_init_feature_metaboxes() {

		global $post;

		if ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = $_REQUEST['post_type'];
		} else {
			if ( isset( $_REQUEST['post'] ) )
				$post_id = (int) $_REQUEST['post'];
			elseif ( isset( $_REQUEST['post_ID'] ) )
				$post_id = (int) $_REQUEST['post_ID'];
			else
				$post_id = 0;

			if ( $post_id )
				$post = get_post( $post_id );

			if ( isset( $post ) && !empty( $post ) )
				$post_type = $post->post_type;
		}

		if ( !empty( $_REQUEST['it-exchange-product-type'] ) )
			$product_type = $_REQUEST['it-exchange-product-type'];
		else
			$product_type = it_exchange_get_product_type( $post );

		if ( !empty( $post_type ) && 'it_exchange_prod' === $post_type ) {
			if ( !empty( $product_type ) &&  it_exchange_product_type_supports_feature( $product_type, 'sku' ) )
				add_action( 'it_exchange_product_metabox_callback_' . $product_type, 'it_exchange_add_product_sku_register_metabox' );
		}

	}
	add_action( 'load-post-new.php', 'it_exchange_add_product_sku_init_feature_metaboxes' );
	add_action( 'load-post.php', 'it_exchange_add_product_sku_init_feature_metaboxes' );

	// Registers the feature metabox for a specific product type
	function it_exchange_add_product_sku_register_metabox() {

		add_meta_box( 'it-exchange-product-sku', __( 'SKU', 'exchange-addon-add-product-sku' ), 'it_exchange_add_product_sku_print_metabox', 'it_exchange_prod', 'it_exchange_side', 'core' );

	}

	function it_exchange_add_product_sku_print_metabox( $post ) {

		// Grab the iThemes Exchange Product object from the WP $post object
		$product = it_exchange_get_product( $post );

		// Set the value of the feature for this product
		$product_feature_value = it_exchange_get_product_feature( $product->ID, 'sku' );

		// Set description
		$description = __( 'SKU', 'exchange-addon-add-product-sku' );
		$description = apply_filters( 'it_exchange_product_sku_metabox_description', $description );

		?>
			<?php if ( $description ) : ?>
			<p class="intro-description">
				<label for="it-exchange-field-product-sku"><?php echo $description; ?><span class="tip" title="This is where a unique identifier (ala Stock Keeping Unit) for each distinct Product is entered.">i</span></label>
			</p>
			<?php endif; ?>
			<p>
				<input type="text" id="it-exchange-field-product-sku" name="it-exchange-product-sku" value="<?php echo esc_textarea( $product_feature_value ); ?>" size="25" style="padding:10px; width:100%;" />
			</p>
		<?php

	}

	// This saves the value
	function it_exchange_add_product_sku_save_feature_on_product_save() {

		// Abort if we can't determine a product type
		if ( ! $product_type = it_exchange_get_product_type() )
			return;

		// Abort if we don't have a product ID
		$product_id = empty( $_POST['ID'] ) ? false : $_POST['ID'];
		if ( ! $product_id )
			return;

		// Abort if this product type doesn't support this feature
		if ( ! it_exchange_product_type_supports_feature( $product_type, 'sku' ) )
			return;

		// Abort if key for feature option isn't set in POST data
		if ( ! isset( $_POST['it-exchange-product-sku'] ) )
			return;

		// Get new value from post
		$new_value = $_POST['it-exchange-product-sku'];

		// Save new value
		it_exchange_update_product_feature( $product_id, 'sku', $new_value );

	}
	add_action( 'it_exchange_save_product', 'it_exchange_add_product_sku_save_feature_on_product_save' );

	/* End of: WordPress Administration */

}

function it_exchange_add_product_sku_add_feature_support_to_product_types() {

	// Register the product feature
	$slug = 'sku';
	$description = 'SKU';
	it_exchange_register_product_feature( $slug, $description );

	// Add it to all enabled product-type addons
	$products = it_exchange_get_enabled_addons( array( 'category' => 'product-type' ) );
	foreach( $products as $key => $params )
		it_exchange_add_feature_support_to_product_type( 'sku', $params['slug'] );

}
add_action( 'it_exchange_enabled_addons_loaded', 'it_exchange_add_product_sku_add_feature_support_to_product_types' );

// This updates the feature for a product
function it_exchange_add_product_sku_save_feature( $product_id, $new_value ) {

	update_post_meta( $product_id, '_it-exchange-product-sku', $new_value );

}
add_action( 'it_exchange_update_product_feature_sku', 'it_exchange_add_product_sku_save_feature', 9, 2 );

// Return the product's features
function it_exchange_add_product_sku_get_feature( $existing, $product_id ) {

	$value = get_post_meta( $product_id, '_it-exchange-product-sku', true );
	return $value;

}
add_filter( 'it_exchange_get_product_feature_sku', 'it_exchange_add_product_sku_get_feature', 9, 2 );

// Does the product have the feature?
function it_exchange_add_product_sku_product_has_feature( $result, $product_id ) {

	// Does this product type support this feature?
	if ( false === $this->product_supports_feature( false, $product_id ) )
		return false;
	return (boolean) $this->get_feature( false, $product_id );

}
add_filter( 'it_exchange_product_has_feature_sku', 'it_exchange_add_product_sku_product_has_feature', 9, 2 );

// Does the product support this feature?
function product_supports_feature( $result, $product_id ) {

	// Does this product type support this feature?
	$product_type = it_exchange_get_product_type( $product_id );
	return it_exchange_product_type_supports_feature( $product_type, 'sku' );

}
add_filter( 'it_exchange_product_supports_feature_sku', 'it_exchange_add_product_sku_product_supports_feature', 9, 2 );
?>