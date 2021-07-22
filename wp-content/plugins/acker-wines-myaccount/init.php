<?php
/*
Plugin Name: Acker Wines // My Account
Plugin URI: https://www.ackerwines.com/
Description: Acker Wines My Account
Version: 1.0
Author: Acker Wines // Anna
Author URI: https://www.ackerwines.com/
*/

if ( ! defined( 'AW_ACCOUNT_DIR' ) ) {
    define( 'AW_ACCOUNT_DIR', plugin_dir_path( __FILE__ ) );
}
global $aw_shared_plugin_path;

require_once( AW_ACCOUNT_DIR . 'preferences/phone-numbers.php');
require_once( AW_ACCOUNT_DIR . 'preferences/account-settings.php');
require_once( AW_ACCOUNT_DIR . 'purchase-history/class-purchase-history.php');
require_once( AW_ACCOUNT_DIR . 'purchase-history/template-purchase-history.php');
require_once( AW_ACCOUNT_DIR . 'portfolio/class-my-wines.php');
require_once( AW_ACCOUNT_DIR . 'portfolio/template-my-wines.php');
require_once( AW_ACCOUNT_DIR . 'dashboard/class-dashboard.php');

require_once( AW_ACCOUNT_DIR . 'favorites-wishlist/template-wishlist.php');
require_once( AW_ACCOUNT_DIR . 'favorites-wishlist/template-favorites.php');
require_once( AW_ACCOUNT_DIR . 'favorites-wishlist/class-favorites.php');
require_once( AW_ACCOUNT_DIR . 'favorites-wishlist/class-wishlist.php');

require_once( AW_ACCOUNT_DIR . 'my-auctions/class-myauctions.php');
require_once( AW_ACCOUNT_DIR . 'my-auctions/templates/bid-history.php');
// require_once( AW_ACCOUNT_DIR . 'my-auctions/templates/appraisals.php');
// require_once( AW_ACCOUNT_DIR . 'my-auctions/templates/active-bids.php');
 require_once( AW_ACCOUNT_DIR . 'my-auctions/templates/consignments.php');

if( ! function_exists('aw_myaccount_init') ){
    function aw_myaccount_init(){
        aw_shared_plugin_init();
    }
}
add_action('init', 'aw_myaccount_init');

add_filter( 'woocommerce_countries', 'aw_woo_country_filter' );
function aw_woo_country_filter($countries)
{
    $usa = $countries['US'];
    unset($countries["US"]);
		$hk = $countries['HK'];
    unset($countries["HK"]);
		$arr = array(
			'US' => $usa,
			'HK' => $hk
		);
    return $arr + $countries;
}

const AW_PURCHASE_HISTORY_ENDPOINT = 'purchase-history';
function aw_account_purchase_history_menu_items($items) {
		$items[AW_PURCHASE_HISTORY_ENDPOINT] = __('Purchase History', 'acker_wines');
		return $items;
}
const AW_MY_WINES_ENDPOINT = 'my-wines';
function aw_account_mywines_menu_items($items) {
		$items[AW_MY_WINES_ENDPOINT] = __('My Wines', 'acker_wines');
		return $items;
}
const AW_FAVORITES_ENDPOINT = 'favorites';
function aw_account_favorites_menu_items($items) {
		$items[AW_FAVORITES_ENDPOINT] = __('Favorites', 'acker_wines');
		return $items;
}
const AW_WISHLIST_ENDPOINT = 'wishlist';
function aw_account_wishlist_menu_items($items) {
		$items[AW_WISHLIST_ENDPOINT] = __('Wishlist', 'acker_wines');
		return $items;
}
const AW_BID_HISTORY_ENDPOINT = 'bid-history';
function aw_account_myauctions_bid_history_menu_items($items) {
		$items[AW_BID_HISTORY_ENDPOINT] = __('Bid History', 'acker_wines');
		return $items;
}
const AW_ACTIVE_BIDS_ENDPOINT = 'active-bids';
function aw_account_myauctions_active_bids_menu_items($items) {
		$items[AW_ACTIVE_BIDS_ENDPOINT] = __('Active Bids', 'acker_wines');
		return $items;
}
const AW_CONSIGNMENTS_ENDPOINT = 'consignments';
function aw_account_myauctions_consignments_menu_items($items) {
		$items[AW_CONSIGNMENTS_ENDPOINT] = __('Consignments', 'acker_wines');
		return $items;
}
const AW_APPRAISAL_ENDPOINT = 'appraisal-submit';
function aw_account_myauctions_appraisal_submit_menu_items($items) {
		$items[AW_APPRAISAL_ENDPOINT] = __('Appraisal Submit', 'acker_wines');
		return $items;
}
function aw_account_purchase_history_content(){
	wp_enqueue_script( 'aw-ajax-common');
	wp_enqueue_script('aw-chosen-jquery-js');
	wp_enqueue_script('aw-jquery-dataTables-js');
	wp_enqueue_script('aw-jquery-ui-js-1.12.1');
	// wp_enqueue_script( 'vue-dev');
	wp_enqueue_script('vue-prod');
	wp_register_script( 'script-purchase-history', plugins_url() . '/acker-wines-myaccount/purchase-history/script-purchase-history.js', array( 'jquery' ), '1.0.1', true );
	wp_enqueue_script( 'script-purchase-history');

	wp_enqueue_style('aw-jquery-ui-css-1.12.1');
	wp_enqueue_style('aw-jquery-dataTables-css');
	wp_register_style( 'account-style', plugins_url('/acker-wines-myaccount/sass/style.css'));
	wp_enqueue_style( 'account-style');
	echo aw_purchase_history_get_template();
}

function aw_account_myauctions_bid_history_content(){
	wp_enqueue_script( 'aw-ajax-common');
	wp_enqueue_script('aw-what-input');
	wp_enqueue_script('aw-chosen-jquery-js');
	wp_enqueue_script('aw-jquery-ui-js-1.12.1');
	wp_enqueue_script('aw-jquery-dataTables-js');
	// wp_enqueue_script( 'vue-dev');
	wp_enqueue_script('vue-prod');
	wp_register_script( 'script-myauctions-bidhistory', plugins_url() . '/acker-wines-myaccount/my-auctions/js/script-myauctions-bidhistory.js', array( 'jquery' ), '1.0.1', true );
	wp_enqueue_script( 'script-myauctions-bidhistory');

	wp_enqueue_style('aw-jquery-ui-css-1.12.1');
	wp_enqueue_style('aw-jquery-dataTables-css');
	wp_register_style( 'account-style', plugins_url('/acker-wines-myaccount/sass/style.css'));
	wp_enqueue_style( 'account-style');

	echo aw_account_myauctions_bid_history_get_template();
}
function aw_account_mywines_content(){
	wp_enqueue_script( 'aw-ajax-common');
	wp_enqueue_script('aw-chosen-jquery-js');
	wp_enqueue_script('aw-jquery-ui-js-1.12.1');
	wp_enqueue_script('aw-highcharts-js');
	wp_enqueue_script('aw-highcharts-data-js');
	wp_enqueue_script('aw-highcharts-more-js');
	wp_enqueue_script('aw-highcharts-regression-js');
	wp_enqueue_script('aw-jquery-dataTables-js');
	wp_register_script( 'script-portfolio', plugins_url() . '/acker-wines-myaccount/portfolio/script-portfolio.js', array( 'jquery' ), '1.0.1', true );
	wp_enqueue_script( 'script-portfolio');

	wp_enqueue_style('aw-jquery-ui-css-1.12.1');
	wp_enqueue_style('aw-jquery-dataTables-css');
	wp_register_style( 'account-style', plugins_url('/acker-wines-myaccount/sass/style.css'));
	wp_enqueue_style( 'account-style');
  echo aw_my_wines_get_template();
}
function aw_account_favorites_content(){
	wp_enqueue_script( 'aw-ajax-common');
	wp_enqueue_script('aw-chosen-jquery-js');
	wp_enqueue_script('aw-jquery-ui-js-1.12.1');
	wp_enqueue_script('aw-jquery-dataTables-js');
	wp_register_script( 'script-favorites', plugins_url() . '/acker-wines-myaccount/favorites-wishlist/script-favorites.js', array( 'jquery' ), '1.0.1', true );
	wp_enqueue_script( 'script-favorites');
	// wp_enqueue_script( 'vue-dev');
	wp_enqueue_script('vue-prod');
	wp_enqueue_style('aw-jquery-ui-css-1.12.1');
	wp_enqueue_style('aw-jquery-dataTables-css');
	wp_register_style( 'account-style', plugins_url('/acker-wines-myaccount/sass/style.css'));
	wp_enqueue_style( 'account-style');
	wp_enqueue_style('fontawesome');

	echo aw_favorites_get_template();
}
function aw_account_wishlist_content(){
	wp_enqueue_script( 'aw-ajax-common');
	wp_enqueue_script('aw-chosen-jquery-js');
	wp_enqueue_script('aw-jquery-ui-js-1.12.1');
	wp_enqueue_script('aw-jquery-dataTables-js');
	wp_register_script( 'script-wishlist', plugins_url() . '/acker-wines-myaccount/favorites-wishlist/script-wishlist.js', array( 'jquery' ), '1.0.1', true );
	wp_enqueue_script( 'script-wishlist');
	// wp_enqueue_script( 'vue-dev');
	wp_enqueue_script('vue-prod');
	wp_enqueue_style('aw-jquery-ui-css-1.12.1');
	wp_enqueue_style('aw-jquery-dataTables-css');
	wp_register_style( 'account-style', plugins_url('/acker-wines-myaccount/sass/style.css'));
	wp_enqueue_style( 'account-style');
	wp_enqueue_style('fontawesome');

	echo aw_wishlist_get_template();
}
function aw_account_myauctions_consignments_content(){
	wp_enqueue_script( 'aw-ajax-common');
	wp_enqueue_script('aw-what-input');
	wp_enqueue_script('aw-chosen-jquery-js');
	wp_enqueue_script('aw-jquery-dataTables-js');
	wp_enqueue_script('aw-jquery-ui-js-1.12.1');
	// wp_enqueue_script( 'vue-dev');
	wp_enqueue_script('vue-prod');
	wp_register_script( 'script-myauctions-consignments', plugins_url() . '/acker-wines-myaccount/my-auctions/js/script-myauctions-consignments.js', array( 'jquery' ), '1.0.1', true );
	wp_enqueue_script( 'script-myauctions-consignments');
	wp_register_style( 'account-style', plugins_url('/acker-wines-myaccount/sass/style.css'));
	wp_enqueue_style( 'account-style');

	echo aw_account_myauctions_consignments_get_template();
}


function aw_rewrite_myaccount_endpoints(){

	add_rewrite_endpoint(AW_PURCHASE_HISTORY_ENDPOINT, EP_ROOT | EP_PAGES);
	add_filter('woocommerce_account_menu_items', 'aw_account_purchase_history_menu_items', 10, 1);
	add_action('woocommerce_account_' . AW_PURCHASE_HISTORY_ENDPOINT . '_endpoint', 'aw_account_purchase_history_content');

	add_rewrite_endpoint(AW_BID_HISTORY_ENDPOINT, EP_ROOT | EP_PAGES);
	add_filter('woocommerce_account_menu_items', 'aw_account_myauctions_bid_history_menu_items', 10, 1);
	add_action('woocommerce_account_' . AW_BID_HISTORY_ENDPOINT . '_endpoint', 'aw_account_myauctions_bid_history_content');

	add_rewrite_endpoint(AW_MY_WINES_ENDPOINT, EP_ROOT | EP_PAGES);
	add_filter('woocommerce_account_menu_items', 'aw_account_mywines_menu_items', 10, 1);
	add_action('woocommerce_account_' . AW_MY_WINES_ENDPOINT . '_endpoint', 'aw_account_mywines_content');

	add_rewrite_endpoint(AW_FAVORITES_ENDPOINT, EP_ROOT | EP_PAGES);
	add_filter('woocommerce_account_menu_items', 'aw_account_favorites_menu_items', 10, 1);
	add_action('woocommerce_account_' . AW_FAVORITES_ENDPOINT . '_endpoint', 'aw_account_favorites_content');

	add_rewrite_endpoint(AW_WISHLIST_ENDPOINT, EP_ROOT | EP_PAGES);
	add_filter('woocommerce_account_menu_items', 'aw_account_wishlist_menu_items', 10, 1);
	add_action('woocommerce_account_' . AW_WISHLIST_ENDPOINT . '_endpoint', 'aw_account_wishlist_content');

	add_rewrite_endpoint(AW_CONSIGNMENTS_ENDPOINT, EP_ROOT | EP_PAGES);
	add_filter('woocommerce_account_menu_items', 'aw_account_myauctions_consignments_menu_items', 10, 1);
	add_action('woocommerce_account_' . AW_CONSIGNMENTS_ENDPOINT . '_endpoint', 'aw_account_myauctions_consignments_content');

	add_rewrite_endpoint(AW_APPRAISAL_ENDPOINT, EP_ROOT | EP_PAGES);
	add_filter('woocommerce_account_menu_items', 'aw_account_myauctions_appraisal_submit_menu_items', 10, 1);
	add_action('woocommerce_account_' . AW_APPRAISAL_ENDPOINT . '_endpoint', 'aw_account_myauctions_appraisal_submit_content');


}
add_action('init', 'aw_rewrite_myaccount_endpoints');


function aw_createFile($data)
{
	$fp = fopen('file.csv', 'w');
	foreach($data as $fields){
		fputcsv($fp, $fields);
	}
	fclose($fp);
}
function aw_sendEmail($to, $subj, $body, $attachments)
{
	global $wp, $wp_query, $wp_the_query;
	$headers = array('Content-Type: text/html; charset=UTF-8', 'From: Acker <wordpress@domain.com>');
	if($attachments != null || $attachments != ''){
		\wp_mail($to, $subject, $message, $headers, $attachments);
	}
	else {
		\wp_mail($to, $subject, $message, $headers );
	}
}
//add_action('plugins_loaded', 'aw_sendEmail');
// $result = aw_sendEmail('annap@ackerwines.com', 'subject', 'message', '');
// echo var_dump($result);
