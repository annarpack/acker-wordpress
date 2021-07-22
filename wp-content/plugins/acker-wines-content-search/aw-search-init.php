<?php
/*
Plugin Name: Acker Wines // Content Search
Plugin URI: https://www.ackerwines.com/
Description: Acker Wines WooCommerce
Version: 1.0
Author: Acker Wines // Anna
Author URI: https://www.ackerwines.com/
*/

if ( ! defined( 'AW_SEARCH_DIR' ) ) {
    define( 'AW_SEARCH_DIR', plugin_dir_path( __FILE__ ) );
}

if( ! function_exists('aw_ajax_search_init') ){
    function aw_ajax_search_init() {
        aw_shared_plugin_init();
        wp_register_script( 'ajax-search', plugins_url() . '/acker-wines-content-search/aw-ajax-search.js', array( 'jquery', 'aw-jquery-ui-js-1.12.1' ), '1.0', true );
        wp_enqueue_script( 'ajax-search');
        // wp_register_style('search-styles', plugins_url() . '/acker-wines-content-search/aw-search-styles.css' );
        // wp_enqueue_style('search-styles');
        wp_enqueue_style('aw-plugins-css');
        wp_enqueue_style('aw-custom-styles');

        require_once(AW_SEARCH_DIR . 'aw-search-form.php');
        require_once(AW_SEARCH_DIR . 'aw-search-page.php');

    }
    add_action( 'init', 'aw_ajax_search_init' );
}//end if
