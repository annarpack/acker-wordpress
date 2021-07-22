<?php
/*
Plugin Name: Acker Wines // API
Plugin URI: https://www.ackerwines.com/
Description: Acker Wines API endpoints for back-end data services.
Version: 1.0
Author: Acker Wines
Author URI: https://www.ackerwines.com/
*/


$includes_path = dirname(__FILE__) . '/includes/';
$classes_path = dirname(__FILE__) . '/classes/';

require_once $includes_path . 'IDataProvider.php';
require_once $includes_path . 'IAckerAPIService.php';
require_once $includes_path . 'IAuctionProgApi.php';
require_once $includes_path . 'IRedisCache.php';
require_once $includes_path . 'Aw_Data.php';
require_once $includes_path . 'UserTraits.php';
require_once $includes_path . 'ApiHelper.php';
require_once $includes_path . 'ApiRequestResult.php';
require_once $includes_path . 'AuctionProgData.php';
require_once $includes_path . 'RedisCache.php';
require_once $includes_path . 'RedisConfig.php';
require_once $includes_path . 'DbCache.php';
require_once $includes_path . 'SearchQuery.php';
require_once $includes_path . 'SearchResultItem.php';

require_once $classes_path . 'AckerApi.php';
require_once $classes_path . 'AuctionProgApi.php';
require_once $classes_path . 'AuctionContact.php';
require_once $classes_path . 'Bid.php';
require_once $classes_path . 'Paddle.php';
require_once $classes_path . 'Lot.php';

use AckerWines\Api\AckerApi;
use AckerWines\Api\ApiRequestResult;
use AckerWines\Api\AuctionProgApi;
use AckerWines\Api\ApiHelper;

/**
 * Auction Prog Endpoints
 */
ApiHelper::registerAjaxEndpoint('aw_auction_invoices', false);
function aw_auction_invoices()
{
    $api = new AuctionProgApi();
    echo $api->getInvoices($api->getApcId());
    wp_die();
}

ApiHelper::registerAjaxEndpoint('aw_auction_invoice_details', false);
function aw_auction_invoice_details()
{
    $paddle_id = $_REQUEST['paddle_id'];
    $api = new AuctionProgApi();
    echo $api->getInvoiceDetails($paddle_id, $api->getApcId());
    wp_die();
}

ApiHelper::registerAjaxEndpoint('aw_auction_invoice_pdf', false);
function aw_auction_invoice_pdf()
{
    $paddle_id = $_REQUEST['paddle_id'];
    $api = new AuctionProgApi();
    $file = $api->getInvoicePdf($paddle_id, $api->getApcId());

    echo ApiRequestResult::formatResultAsJson($file);

    wp_die();
}

ApiHelper::registerAjaxEndpoint('aw_auction_total_balance', false);
function aw_auction_total_balance()
{
    $api = new AuctionProgApi();
    echo $api->getTotalBalance($api->getApcId());
    wp_die();
}

ApiHelper::registerAjaxEndpoint('aw_auction_bids', false);
function aw_auction_bids()
{
    $auctionId = isset($_REQUEST['auctionId']) ? ApiHelper::zero2null($_REQUEST['auctionId']) : null;
    $startDate = $_REQUEST['startDate'];
    $endDate = $_REQUEST['endDate'];

    $api = new AuctionProgApi();
    echo $api->getBids($api->getApcId(), $auctionId, $startDate, $endDate);

    wp_die();
}

ApiHelper::registerAjaxEndpoint('aw_consignor_sales', false);
function aw_consignor_sales()
{
    $startDate = $_REQUEST['startDate'];

    $api = new AuctionProgApi();
		//test api id = 1821
    //echo $api->getConsignorSales($api->getApcId(), $startDate);
		echo $api->getConsignorSales(1821, $startDate);

    wp_die();
}

/**
 * WordPress Data Endpoints
 */
ApiHelper::registerAjaxEndpoint('aw_search_global', true);
function aw_search_global()
{
    $search_term = $_POST['searchTerm'];
    $search_category = $_POST['category'];
    $post_count = $_POST['postCount'];
    $api = new AckerApi();
    echo $api->globalSearch($search_term, $search_category, $post_count);
    wp_die();
}

ApiHelper::registerAjaxEndpoint('aw_ajax_get_purchase_history', true);
function aw_ajax_get_purchase_history()
{
    $api = new AckerApi();
    echo $api->getPurchaseHistory();
    wp_die();
}

ApiHelper::registerAjaxEndpoint('aw_ajax_get_dashboard', true);
function aw_ajax_get_dashboard()
{
    $api = new AckerApi();
    echo $api->getDashboard();
    wp_die();
}

ApiHelper::registerAjaxEndpoint('aw_ajax_get_portfolio_wines', true);
function aw_ajax_get_portfolio_wines()
{
    $api = new AckerApi();
    echo $api->getPortfolioWines();
    wp_die();
}

ApiHelper::registerAjaxEndpoint('aw_ajax_get_favorites', true);
function aw_ajax_get_favorites()
{
    $api = new AckerApi();
    echo $api->getFavorites();
    wp_die();
}

ApiHelper::registerAjaxEndpoint('aw_ajax_remove_favorite', true);
function aw_ajax_remove_favorite()
{
    $product_id = $_POST['product_id'];
    $wishlist_id = $_POST['wishlist_id'];
    $api = new AckerApi();
    echo $api->removeFavorite($product_id, $wishlist_id);
    wp_die();
}

ApiHelper::registerAjaxEndpoint('aw_ajax_get_wishlist', true);
function aw_ajax_get_wishlist()
{
    $api = new AckerApi();
    echo $api->getWishlist();
    wp_die();
}
ApiHelper::registerAjaxEndpoint('aw_ajax_save_appraisal', true);
function aw_ajax_save_appraisal()
{
		$data = $_POST['data'];
		$page = $_POST['page'];
    $api = new AckerApi();
    echo $api->saveAppraisalTable($data, $page);
    wp_die();
}


/**
 * Activation hook
 */
function acker_wines_api_activation()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = <<<SQL
CREATE TABLE `aw_prog_cache`  (
  `key` varchar(255) NOT NULL,
  `value` longtext NULL,
  `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE INDEX `key_idx`(`key`) USING BTREE
) $charset_collate;

CREATE TABLE `aw_prog_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `file` longblob NOT NULL,
  `file_type` varchar(60) NOT NULL,
  `file_size` int(10) unsigned NOT NULL,
  `document_key` varchar(255) NULL,
  `aw_apcid` varchar(255) NULL,
  `document_type` varchar(255) NULL,
  `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_prog_file_name` (`name`) USING BTREE
) $charset_collate;

CREATE TABLE `aw_appraisal_requests` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`key` varchar(10) NOT NULL,
	`user_id` varchar(10) NOT NULL,
	`date` varchar(60) NOT NULL,
	`number` varchar(255) NOT NULL,
	`qty`	varchar(60) NOT NULL,
	`format` varchar(255) NOT NULL,
	`vintage` varchar(255) NOT NULL,
	`wine_name` varchar(255) NOT NULL,
	`region` varchar(255) NOT NULL,
	`designation` varchar(255) NOT NULL,
	`producer` varchar(255) NOT NULL,
	`data` longtext NOT NULL,
	`email_sent` varchar(60) NOT NULL,
	`file_upload` varchar(255) NOT NULL,
	`status` varchar(60) NOT NULL,
	`timestamp` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) $charset_collate;

CREATE TABLE `aw_appraisal_inbounds` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`aw_apcid` varchar(50) NOT NULL,
	`method` varchar(50) NOT NULL,
	`date` varchar(60) NOT NULL,
	`qty`	varchar(10) NOT NULL,
	`timestamp` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) $charset_collate;

CREATE TABLE `aw_appraisals` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`aw_apcid` varchar(50) NOT NULL,
	`batch_id` varchar(50) NOT NULL,
	`date` varchar(60) NOT NULL,
	`date_suffix` varchar(10) NOT NULL,
	`qty`	varchar(10) NOT NULL,
	`status`	varchar(10) NOT NULL,
	`timestamp` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) $charset_collate;

SQL;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

if (function_exists('register_activation_hook')) {
    register_activation_hook(__FILE__, 'acker_wines_api_activation');
}
