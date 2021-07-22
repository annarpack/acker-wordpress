<?php

if ( ! defined( 'AW_ACCOUNT_DIR' ) ) {
    define( 'AW_ACCOUNT_DIR', plugin_dir_path( __FILE__ ) );
}
require_once( AW_ACCOUNT_DIR . '/purchase-history/class-purchase-history.php');
const AW_PURCHASE_HISTORY_ENDPOINT = 'purchase-history';

function aw_purchase_history_init() {
    aw_shared_plugin_init();
    add_rewrite_endpoint(AW_PURCHASE_HISTORY_ENDPOINT, EP_ROOT | EP_PAGES);
}
add_action('init', 'aw_purchase_history_init');

function aw_purchase_history_account_menu_items($items) {
    $items[AW_PURCHASE_HISTORY_ENDPOINT] = __('Purchase History', 'acker_wines');
    return $items;
}
add_filter('woocommerce_account_menu_items', 'aw_purchase_history_account_menu_items', 10, 1);

function aw_purchase_history_shortcode( $atts ){
    $filter = $atts['filter'];
    $page = $atts['page'];
    // echo '<p>' . var_dump($filter) . '</p>';
    // echo '<p>' . var_dump($page) . '</p>';

    $a = shortcode_atts( array(
		'filter' => $filter,
        'page' => $page
	), $atts );
    AckerWines\AW_Purchase_History::aw_purchase_history_get_template($page, $filter);
}
add_shortcode('aw_purchase_history', 'aw_purchase_history_shortcode');


function aw_purchase_history_filter_click(){
    $filter = $_POST['filter'];
    //echo '<p>' . var_dump($filter) . '</p>';
    if( !isset($filter) ){
        $filter = 'invoices';
    }
    $retail = array('shop_order', 'shop_subscription', 'tickets');
    $index = array_search($filter, $retail);
    //echo '<p>' . var_dump($index) . '</p>';
    if($filter == 'invoices'){
        AckerWines\AW_Purchase_History::aw_purchase_history_show_content('invoices', 'none');
    }
    if(isset($index)){
        AckerWines\AW_Purchase_History::aw_purchase_history_show_content('products', $filter);
    }
    // if($filter == 'auctions'){
    //     // aw_my_auctions_content_get();
    // }

}
add_action( 'wp_ajax_aw_purchase_history_filter_click', 'aw_purchase_history_filter_click' );
add_action( 'wp_ajax_nopriv_aw_purchase_history_filter_click', 'aw_purchase_history_filter_click' );

function aw_purchase_history_content() {
    echo AckerWines\AW_Purchase_History::aw_purchase_history_filter_buttons();
    echo '<div id="filtered-content">';
        AckerWines\AW_Purchase_History::aw_purchase_history_show_content('invoices', 'none');
    echo '</div>';

} // end func my orders content
add_action('woocommerce_account_' . AW_PURCHASE_HISTORY_ENDPOINT . '_endpoint', 'aw_purchase_history_content');
