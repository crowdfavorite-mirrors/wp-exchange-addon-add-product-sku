<?php
/*
 * Plugin Name: iThemes Exchange - Add Product SKU
 * Plugin URI: http://www.visser.com.au/exchange/plugins/add-product-sku/
 * Description: This addon for iThemes Exchange adds SKU support to all Product Types.
 * Version: 1.1
 * Author: Visser Labs
 * Author URI: http://www.visser.com.au/about/
 * License: GPL2
 * iThemes Package: exchange-addon-exporter

 * Installation:
 * 1. Download and unzip the latest release zip file.
 * 2. If you use the WordPress Plugin uploader to install this plugin skip to step 4.
 * 3. Upload the entire plugin directory to your `/wp-content/plugins/` directory.
 * 4. Activate the plugin through the 'Plugins' menu in WordPress Administration.
 *
*/

/**
 * This registers our plugin as an add-on with iThemes Exchange
 *
 * @since 1.0
 *
 * @return void
*/
function it_exchange_add_product_sku_register_addon() {

  $options = array(
      'name'              => __( 'Add Product SKU', 'exchange-addon-add-product-sku' ),
      'description'       => __( 'Adds SKU support to all Product Types. There are no settings for this add-on.', 'exchange-addon-add-product-sku' ),
      'author'            => 'Visser Labs',
      'author_url'        => 'http://www.visser.com.au/about/',
      'icon'              => ITUtility::get_url_from_file( dirname( __FILE__ ) . '/images/other50px.png' ),
      'file'              => dirname( __FILE__ ) . '/init.php',
      'category'          => 'other'
  );
  it_exchange_register_addon( 'add-product-sku', $options );
  
}
add_action( 'it_exchange_register_addons', 'it_exchange_add_product_sku_register_addon' );

/**
 * Loads the translation data for WordPress
 *
 * @uses load_plugin_textdomain()
 * @since 1.0
 * @return void
*/
function it_exchange_add_product_sku_i18n() {

	load_plugin_textdomain( 'exchange-addon-add-product-sku', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}
add_action( 'plugins_loaded', 'it_exchange_add_product_sku_i18n' );
?>