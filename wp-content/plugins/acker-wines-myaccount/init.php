<?php
/*
Plugin Name: Acker Wines // My Account
Plugin URI: https://www.ackerwines.com/
Description: Acker Wines WooCommerce
Version: 1.0
Author: Acker Wines // Anna
Author URI: https://www.ackerwines.com/
*/
if ( ! defined( 'AW_ACCOUNT_DIR' ) ) {
    define( 'AW_ACCOUNT_DIR', plugin_dir_path( __FILE__ ) );
}

spl_autoload_register(function ($class_name) {
    @include_once plugin_dir_path(__FILE__) . '/includes/' . $class_name . '.php';
});

require_once( AW_ACCOUNT_DIR . 'aw-purchase-history.php');
// require_once( AW_ACCOUNT_DIR . 'aw-my-invoices.php');
// require_once( AW_ACCOUNT_DIR . 'aw-my-products.php');
// require_once( AW_ACCOUNT_DIR . 'aw-phone-numbers.php');
// require_once( AW_ACCOUNT_DIR . 'aw-my-wishlist.php');
// require_once( AW_ACCOUNT_DIR . 'aw-my-favorites.php');
require_once( AW_ACCOUNT_DIR . 'aw-my-auctions-invoices.php');
require_once( AW_ACCOUNT_DIR . 'my-auctions/my-auctions.php');

//echo AW_ACCOUNT_DIR;
if( ! function_exists('aw_myaccount_init') ){
    function aw_myaccount_init(){
        aw_shared_plugin_init();
        wp_enqueue_script('aw-chosen-jquery-js');
        wp_enqueue_script('aw-jquery-ui-js');
        wp_enqueue_script('aw-jquery-dataTables-js');
        wp_enqueue_style('aw-jquery-ui-css-1.12.1');
        wp_enqueue_style('aw-jquery-dataTables-css');
        wp_enqueue_style('aw-plugins-css');
        wp_enqueue_style('aw-custom-styles');

        wp_enqueue_script( 'aw-ajax-common' );
        wp_localize_script( 'aw-ajax-common', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ));

        wp_register_script( 'purchase-history', plugins_url() . '/acker-wines-myaccount/aw-purchase-history.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'purchase-history');
        //wp_register_script( 'auction-invoices', plugins_url() . '/acker-wines-myaccount/aw-my-auction-invoices.js', array( 'jquery' ), '1.0', true );
        //wp_enqueue_script( 'auction-invoices');

        wp_localize_script( 'purchase-history', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

        wp_register_script( 'purchase-history', plugins_url() . '/acker-wines-myaccount/aw-purchase-history.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'purchase-history');

        // wp_register_style( 'account-styles', plugins_url() . '/acker-wines-myaccount/aw-myaccount-styles.css' );
        // wp_enqueue_style( 'account-styles');

    }
}
add_action('init', 'aw_myaccount_init');
//
// function aw_purchase_history_shortcode( $atts ){
//     $a = shortcode_atts( array(
// 		'filter' => 'invoices'
// 	), $atts );
//     aw_purchase_history_content($a);
// }
// add_shortcode('aw_purchase_history', 'aw_purchase_history_shortcode');

/**
 * Activation hook
 */
function aw_myaccount_activation() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = <<<SQL
CREATE TABLE `aw_prog_cache`  (
  `key` varchar(255) NOT NULL,
  `value` longtext NULL,
   `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE INDEX `key_idx`(`key`) USING BTREE
) $charset_collate;
SQL;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook(__FILE__, 'aw_myaccount_activation');
