<?php

namespace AckerWines\Api;

use AckerWines\AW_Favorites;
use AckerWines\AW_MY_Wines;
use AckerWines\AW_Purchase_History;
use AckerWines\AW_Dashboard;
use AckerWines\AW_Wishlist;
use AckerWines\Api\AuctionProgApi;

$dir_prefix =  dirname(__FILE__) . '/../../../mu-plugins/acker-wines-shared/conf/';
include_once $dir_prefix . 'config.php';
include_once $dir_prefix . 'common.php';
include_once $dir_prefix . 'db.php';

class AckerApi implements IAckerAPIService
{
    use UserTraits;

    function globalSearch(string $search_term, string $search_category, int $post_count)
    {
			if(class_exists('AW_Search_Form')){
        $search_api = new SearchQuery();
        $search_results = $search_api->getResults($search_term, $search_category, $post_count);
				//$search_results = $search_api->skuSearch($search_term);
				//echo var_dump($search_results);
        return $search_results;
			}
			else {
				return 'activate search plugin';
			}
    }

    function getPurchaseHistory()
    {
			if(class_exists('AckerWines\AW_Purchase_History')){
        $history_api = new AW_Purchase_History();
        $results = $history_api->aw_purchase_history_get_combined_data();
        $data = ApiRequestResult::formatResultAsJson($results);
        return $data;
			}
			else {
				return 'activate purchase history plugin';
			}
    }
		function getDashboard()
		{
			if(class_exists('AckerWines\AW_Dashboard')){
				$history_api = new AW_Dashboard();
				$results = $history_api->aw_dashboard_get_combined_data();
				$data = ApiRequestResult::formatResultAsJson($results);
				return $data;
			}
			else {
				return 'activate dashboard plugin';
			}
		}

    function getPortfolioWines()
    {
        $wines_api = new AW_MY_Wines();
				$all_results = $wines_api->aw_my_wines_get_combined_data();
        $data = ApiRequestResult::formatResultAsJson($all_results);
        return $data;
    }

    function getFavorites()
    {
			if(class_exists('AckerWines\AW_Favorites')){
        $favorites = new AW_Favorites();
        $results = $favorites->aw_favorites_get_items();
        $data = ApiRequestResult::formatResultAsJson($results);
        return $data;
			}
			else {
				return 'activate favorites plugin';
			}
    }
		function removeFavorite(string $product_id, string $wishlist_id)
		{
			if(class_exists('AckerWines\AW_Favorites')){
				$favorites = new AW_Favorites();
				$results = $favorites->aw_favorites_remove_item();
				$data = ApiRequestResult::formatResultAsJson($results);
				return $data;
			}
			else {
				return 'activate favorites plugin';
			}
		}
    function getWishlist()
    {
			if(class_exists('AckerWines\AW_Wishlist')){
        $wishlist = new AW_Wishlist();
        $results = $wishlist->aw_wishlist_get_items();
        $data = ApiRequestResult::formatResultAsJson($results);
        return $data;
			}
			else {
				return 'activate wishlist plugin';
			}
    }
		function getAppraisalNum()
		{
			$aw_mysql_conn = aw_mysqlDbConnect();
			if(!$aw_mysql_conn){
				aw_logMessage("couldnt connect to database");
				return "couldnt connect to database";
			}
			$key_result = false; $key_value = NULL;
$key_sql = <<<SQL
SELECT `key` FROM aw_appraisal_requests ORDER BY `key` DESC LIMIT 1;
SQL;
			$stmt = $aw_mysql_conn->prepare($key_sql);
			if ($stmt->execute()) {
				$stmt->bind_result($key_value);
				$stmt->fetch();
			}
			$key_value++;
			return $key_value;
		}
		function saveAppraisalTable($data, $page)
		{
			$aw_mysql_conn = aw_mysqlDbConnect();
			if(!$aw_mysql_conn){
				aw_logMessage("couldnt connect to database");
				return "couldnt connect to database";
			}
			$key_value = $this->getAppraisalNum();
			$result = false; $main_sql;
			$user_id = $atts['user'] ? $atts['user'] : get_current_user_id();
			$user_id = mysqli_real_escape_string($aw_mysql_conn, $user_id);
			foreach($data as $elm){
				if($page == 'my-wines'){
					$key = mysqli_real_escape_string($aw_mysql_conn, $key_value);
					$date = mysqli_real_escape_string($aw_mysql_conn, $elm['order_date']);
					$number = mysqli_real_escape_string($aw_mysql_conn, $elm['order_number']);
					$qty = mysqli_real_escape_string($aw_mysql_conn, $elm['wine_qty']);
					$format = mysqli_real_escape_string($aw_mysql_conn, $elm['wine_format']);
					$vintage = mysqli_real_escape_string($aw_mysql_conn, $elm['wine_vintage']);
					$wine_name = mysqli_real_escape_string($aw_mysql_conn, $elm['wine_name']);
					$region = mysqli_real_escape_string($aw_mysql_conn, $elm['wine_region']);
					$designation = mysqli_real_escape_string($aw_mysql_conn, $elm['wine_designation']);
					$producer = mysqli_real_escape_string($aw_mysql_conn, $elm['wine_producer']);
					$elm_data = json_encode($elm);
					$elm_data = mysqli_real_escape_string($aw_mysql_conn, $elm_data);

$main_sql = <<<SQL
INSERT INTO aw_appraisal_requests (`key`, `user_id`, `date`, `number`, `qty`, `format`, `vintage`, `wine_name`, `region`, `designation`, `producer`, `data`, `email_sent`, `file_upload`, `status`)
VALUES ("$key", "$user_id", "$date", "$number", "$qty", "$format", "$vintage", "$wine_name", "$region", "$designation", "$producer", "$elm_data", "false", "false", "false");
SQL;
				}
				if($page == 'bid-history'){
					$key = mysqli_real_escape_string($aw_mysql_conn, $key_value);
					//$date = mysqli_real_escape_string($aw_mysql_conn, $elm['lotId']);
					$number = mysqli_real_escape_string($aw_mysql_conn, $elm['lotId']);
					$quantity = mysqli_real_escape_string($aw_mysql_conn, $elm['quantity']);
					$format = mysqli_real_escape_string($aw_mysql_conn, $elm['format']);
					$vintage = mysqli_real_escape_string($aw_mysql_conn, $elm['vintage']);
					$wine_name = mysqli_real_escape_string($aw_mysql_conn, $elm['wineName']);
					$designation = mysqli_real_escape_string($aw_mysql_conn, $elm['designation']);
					$producer = mysqli_real_escape_string($aw_mysql_conn, $elm['producer']);
					$bidAmount = mysqli_real_escape_string($aw_mysql_conn, $elm['bidAmount']);
					$result = mysqli_real_escape_string($aw_mysql_conn, $elm['result']);

					$elm_data = json_encode($elm);
					$elm_data = mysqli_real_escape_string($aw_mysql_conn, $elm_data);

$main_sql = <<<SQL
INSERT INTO aw_appraisal_requests (`key`, `user_id`, `date`, `number`, `qty`, `format`, `vintage`, `wine_name`, `region`, `designation`, `producer`, `data`, `email_sent`, `file_upload`, `status`)
VALUES ("$key", "$user_id", "$date", "$number", "$qty", "$format", "$vintage", "$wine_name", "$region", "$designation", "$producer", "$elm_data", "false", "false", "false");
SQL;
				}
					try {
							$result = $aw_mysql_conn->query($main_sql);
					} catch(\Exception $e) {
							error_log($e->getMessage(), 0);
					}
			}
			// $a = array($key_value, $key, $result);
			// return json_encode($a);
			return $result;
		}

}
