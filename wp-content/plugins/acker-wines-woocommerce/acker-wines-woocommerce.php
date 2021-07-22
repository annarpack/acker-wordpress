<?php
/*
Plugin Name: Acker Wines // WooCommerce
Plugin URI: https://www.ackerwines.com/
Description: Acker Wines WooCommerce
Version: 1.0
Author: Acker Wines // Yair
Author URI: https://www.ackerwines.com/
*/

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// TODO: Add permissions check on updating inventory

const AW_WC_FIELD_MAX_STOCK_NAME = '_aw_max_stock_display';
const AW_WC_FIELD_MAX_STOCK_LABEL = 'Max stock to display';
const AW_WC_FIELD_MAX_STOCK_DESCRIPTION = 'Please enter the max number of stock the customer should see when shopping.';

// Determine if running back-end or
if (is_admin()) {
	// Show entry field for simple product
	function aw_wc_add_custom_inventory_fields() {
		global $post;
		
		// Get the field value if exists
		$max_stock_display = get_post_meta($post->ID, AW_WC_FIELD_MAX_STOCK_NAME, true);
		if (!$max_stock_display) $max_stock_display = '';

		// Display the field
		woocommerce_wp_text_input(array(
			'id'          => AW_WC_FIELD_MAX_STOCK_NAME,
			'label'       => __(AW_WC_FIELD_MAX_STOCK_LABEL, 'woocommerce'),
			'desc_tip'    => 'true',
			'description' => __(AW_WC_FIELD_MAX_STOCK_DESCRIPTION, 'woocommerce'),
			'type'        => 'number',
		), $max_stock_display);
	}
	add_action('woocommerce_product_options_inventory_product_data', 'aw_wc_add_custom_inventory_fields');

	// Save entry field for simple product
	function aw_wc_add_custom_inventory_fields_save($post_id) {
		if (isset($_POST[AW_WC_FIELD_MAX_STOCK_NAME])) {
			update_post_meta($post_id, AW_WC_FIELD_MAX_STOCK_NAME, sanitize_text_field($_POST[AW_WC_FIELD_MAX_STOCK_NAME]));
		}
	}
	add_action('woocommerce_process_product_meta', 'aw_wc_add_custom_inventory_fields_save');

	// Show entry field for product variations
	function aw_wc_add_custom_inventory_variations_fields($loop, $variation_data, $variation) {
		$max_stock_display = get_post_meta($variation->ID, AW_WC_FIELD_MAX_STOCK_NAME, true);
		if (!$max_stock_display) $max_stock_display = '';

		woocommerce_wp_text_input(array(
			'id'          => AW_WC_FIELD_MAX_STOCK_NAME . '_' . $loop,
			'label'       => __(AW_WC_FIELD_MAX_STOCK_LABEL, 'woocommerce'),
			'desc_tip'    => 'true',
			'description' => __(AW_WC_FIELD_MAX_STOCK_DESCRIPTION, 'woocommerce'),
			'value'       => $max_stock_display,
		));
	}
	add_action('woocommerce_product_after_variable_attributes', 'aw_wc_add_custom_inventory_variations_fields', 10, 3);

	// Save entry field for product variation
	function aw_wc_add_custom_inventory_variations_fields_save($variation_id, $loop) {
		if (isset($_POST[AW_WC_FIELD_MAX_STOCK_NAME . '_' . $loop])) {
			update_post_meta($variation_id, AW_WC_FIELD_MAX_STOCK_NAME, sanitize_text_field($_POST[AW_WC_FIELD_MAX_STOCK_NAME . '_' . $loop]));
		}
	}
	add_action('woocommerce_save_product_variation', 'aw_wc_add_custom_inventory_variations_fields_save', 10 , 2);
} else {
	// Process max stock display
	function aw_wc_custom_get_stock_quantity($value, $product) {
		$max_stock_display = get_post_meta($product->get_id(), AW_WC_FIELD_MAX_STOCK_NAME, true);
		if ($max_stock_display) {
			return min($max_stock_display, $value);
		}
		
		return $value;
	}
	add_filter('woocommerce_product_get_stock_quantity', 'aw_wc_custom_get_stock_quantity', 10, 2);
	add_filter('woocommerce_product_variation_get_stock_quantity' ,'aw_wc_custom_get_stock_quantity', 10, 2);
}