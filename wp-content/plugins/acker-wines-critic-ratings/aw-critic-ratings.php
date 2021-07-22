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
}
add_action('init', 'aw_critic_ratings_init');
/*
 * Tab
 */
add_filter('woocommerce_product_data_tabs', 'aw_critic_ratings_settings_tabs' );
function aw_critic_ratings_settings_tabs( $tabs ){

	$tabs['critic_rating'] = array(
		'label'    => 'Critic Rating',
		'target'   => 'critic_rating_data',
		'priority' => 21,
	);
	return $tabs;

}

/*
 * Tab content
 */
add_action( 'woocommerce_product_data_panels', 'aw_critic_ratings_panels' );
function aw_critic_ratings_panels(){

	echo '<div id="critic_rating_data" class="panel woocommerce_options_panel hidden">';
    echo '<h2>Critic #1</h2>';
	woocommerce_wp_text_input( array(
		'id'      => 'critic_initials_1',
		'value'   => get_post_meta( get_the_ID(), 'critic_initials_1', true ),
		'label'   => 'Critic Initials',
		'desc_tip' => true,
		'description' => 'Put wine critics initials here',
	) );

	woocommerce_wp_text_input( array(
		'id'      => 'critic_rating_1',
		'value'   => get_post_meta( get_the_ID(), 'critic_rating_1', true ),
		'label'   => 'Critic Rating',
		'desc_tip' => true,
		'description' => 'Put wine critics rating here',
	) );
    echo '<h2>Critic #2</h2>';
    woocommerce_wp_text_input( array(
        'id'      => 'critic_initials_2',
        'value'   => get_post_meta( get_the_ID(), 'critic_initials_2', true ),
        'label'   => 'Critic Initials',
        'desc_tip' => true,
        'description' => 'Put wine critics initials here',
    ) );

    woocommerce_wp_text_input( array(
        'id'      => 'critic_rating_2',
        'value'   => get_post_meta( get_the_ID(), 'critic_rating_2', true ),
        'label'   => 'Critic Rating',
        'desc_tip' => true,
        'description' => 'Put wine critics rating here',
    ) );
    echo '<h2>Critic #3</h2>';
    woocommerce_wp_text_input( array(
        'id'      => 'critic_initials_3',
        'value'   => get_post_meta( get_the_ID(), 'critic_initials_3', true ),
        'label'   => 'Critic Initials',
        'desc_tip' => true,
        'description' => 'Put wine critics initials here',
    ) );

    woocommerce_wp_text_input( array(
        'id'      => 'critic_rating_3',
        'value'   => get_post_meta( get_the_ID(), 'critic_rating_3', true ),
        'label'   => 'Critic Rating',
        'desc_tip' => true,
        'description' => 'Put wine critics rating here',
    ) );

	echo '</div>';

}


add_action('woocommerce_process_product_meta', 'aw_critic_ratings_save_fields');
function aw_critic_ratings_save_fields(){
	//$post = get_the_post();
	$id = get_the_ID();
    update_post_meta( $id, 'critic_initials_1', $_POST['critic_initials_1'] );
	update_post_meta( $id, 'critic_rating_1', $_POST['critic_rating_1'] );
    update_post_meta( $id, 'critic_initials_2', $_POST['critic_initials_2'] );
	update_post_meta( $id, 'critic_rating_2', $_POST['critic_rating_2'] );
    update_post_meta( $id, 'critic_initials_3', $_POST['critic_initials_3'] );
	update_post_meta( $id, 'critic_rating_3', $_POST['critic_rating_3'] );
}
