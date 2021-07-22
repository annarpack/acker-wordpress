<?php
/*
Plugin Name: Acker Wines // Critic Ratings
Plugin URI: https://www.ackerwines.com/
Description: Acker Wines WooCommerce
Version: 1.0
Author: Acker Wines // Anna
Author URI: https://www.ackerwines.com/
*/
global $product, $wp;

function aw_critic_ratings_init() {
    aw_shared_plugin_init();
	wp_enqueue_style('aw-plugins-css');
    require_once('aw-critic-ratings.php');

    // return AW_Critic_Rating::instance();
}
add_action('init', 'aw_critic_ratings_init');
