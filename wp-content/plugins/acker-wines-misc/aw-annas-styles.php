<?php
/*
Plugin Name: Acker Wines // Anna's Styles
Plugin URI: https://www.ackerwines.com/
Description: Acker Wines Styles
Version: 1.0
Author: Acker Wines // Anna
Author URI: https://www.ackerwines.com/
*/

if( ! function_exists('aw_custom_styles_init') ){
    function aw_custom_styles_init() {
        aw_shared_plugin_init();
        // wp_register_script( 'ajax-search', plugins_url() . '/acker-wines-content-search/aw-ajax-search.js', array( 'jquery', 'aw-jquery-ui-js-1.12.1' ), '1.0', true );
        // wp_enqueue_script( 'ajax-search');
        wp_enqueue_style('aw-plugins-css');
        wp_register_style('aw-custom-styles', plugins_url() . '/acker-wines-misc/sass/style.css' );
        wp_enqueue_style('aw-custom-styles');

        wp_register_script('aw-nav-scripts', plugins_url() . '/acker-wines-misc/aw-nav-scripts.js' );
        wp_enqueue_script('aw-nav-scripts');

        require_once(AW_SEARCH_DIR . 'aw-search-form.php');
        require_once(AW_SEARCH_DIR . 'aw-search-page.php');

    }
    add_action( 'init', 'aw_custom_styles_init' );
}//end if
