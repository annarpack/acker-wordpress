<?php
namespace AckerWines\Api;
if (!defined('ABSPATH')) exit;
class SearchQuery
{
    public static $labelArr = array(  'All', 'Auction', 'Retail', 'Auction & Retail', 'Workshops & Events', 'Tasting Notes',  'FAQ' );
    public static $valueArr = array( 'auction', 'product', 'auction-retail', 'class', 'vintage-tastings', 'faq' );
    public static $optionArr = array( 'auction', 'product', 'auction-retail', 'event', 'tastings', 'faq' );

    public function searchFormShortcode( $atts ){
      $a = shortcode_atts( array(
    		'searchTerm' => 'searchTerm',
        'category' => 'category'
    	), $atts );
        //$this->aw_get_search_form($a);
    }
    function getSearchArgs(string $search_term, string $category, int $post_count){
        $post_query = $category;
        switch($post_query){
            case 'event':
                $post_query = 'class';
                break;
            case 'tastings':
                $post_query = 'post';
                break;
            case 'faq':
                $post_query = 'faq';
                break;
            default:
                $post_query = array('post', 'page', 'product');
        }
        $args = array(
             's' => $search_term,
             'post_type' => $post_query,
             'posts_per_page' => $post_count
        );
        if($category == 'tastings'){
            $args = array(
             's' => $search_term,
             'post_type' => 'post',
             'post_category' => 'vintage-tastings',
             'posts_per_page' => $post_count
            );
        }
				if($category == 'product'){
            $args = array(
             's' => $search_term,
             'post_type' => array( 'product', 'product_variation' ),
						 'tax_query' => array(
							 'relation' => 'OR',
	 							array(
	 								'taxonomy' => 'product_cat',
	 								'field'    => 'slug',
	 								'terms'    => array('retail', 'fine-rare'),
	 							),
							),
             'posts_per_page' => $post_count
          );
        }
			 if($category == 'auction'){
				$args = array(
					 'post_type' => array( 'product', 'product_variation' ),
					 'tax_query' => array(
						 array(
							 'taxonomy' => 'product_cat',
							 'field'    => 'slug',
							 'terms'    => 'auction',
						 ),
						),
						'meta_query' => array(
							array(
								'key' => 'wpcf-search-terms',
								'value' => $search_term,
								'compare' => 'LIKE'
							)
						),
			 		 'posts_per_page' => $post_count
			 		);
			 }
			 if($category == 'auction-retail'){
				 $args = array(
						's' => $search_term,
						'post_type' => array( 'product', 'product_variation' ),
						'posts_per_page' => $post_count
					 );
			 }
			 return $args;
    }
    function getQueryArgs(string $search_term, string $search_category)
    {
        $query_args = array(
            'query_args' => array(
                'term' => $search_term,
                'category' => $search_category
            )
        );
        return $query_args;
    }
    function getWPQuery(string $search_term, string $search_category, int $post_count)
    {
				$log_string = aw_logMessageStr("Start processing '{$search_term}' in category '{$search_category}'");
        $args = $this->getSearchArgs($search_term, $search_category, $post_count);
        $form_query = new \WP_Query( $args );
        $results = NULL;
        if($form_query->have_posts()){
            $posts_arr = array();
            while($form_query->have_posts()){
							$log_string .= aw_logMessageStr("Process data");
                $form_query->the_post();
                $id = get_the_ID();
                $title = get_the_title($id);
                $short_title = $title;
                $length = strlen(strval($title));
                if($length > 100){
                    $short_title = substr( $title , 0, 101);
                    $short_title .= '...';
                }
                $title = esc_attr($title);
                $link = get_the_permalink();
                $thumb = get_the_post_thumbnail_url();
                $desc = get_the_excerpt($id);
                if($search_category == 'product' || $search_category == 'auction-retail' || $search_category == 'auction'){
										//global $product;
                    $product = \wc_get_product($id);
										//$product->get_sku();
										$product_id = $product->get_id();
										//$attributes = (array) $product->get_attributes();
										//$terms = wp_get_product_terms($product_id);
										//$flavors = $attributes->pa_flavor;
										//$atts = wc_get_attribute_taxonomies();
                    $price = $product->get_price_html();
										// $product_type_name;
										// $product_type = wp_get_object_terms($id, 'product_type')[0];
										// if(isset($product_type)){
										// 	$product_type_name = $product_type->name;
										// }
										$product_cats = wp_get_post_terms( $product->id, 'product_cat', array('orderby' => 'slug') );
										//$product_cat = $product_cats[0]->slug;
										$product_type = 'retail';
										if($search_category == 'auction'){ $product_type = 'auction'; }
										if($search_category == 'product'){ $product_type = 'retail'; }
										if($search_category == 'auction-retail'){
											$product_cats = wp_get_post_terms( $product->id, 'product_cat', array('orderby' => 'slug') );
											$product_type = $product_cats[0]->slug;
										}
										if(!isset($thumb)){
											$image_id = $product->get_image_id();
											$thumb = wp_get_attachment_image_url( $image_id, 'thumbnail' );
											if(!isset($thumb)){
												$thumb = 'https://ackerwines.co/webimages/Acker_Bottle-placeholder.png';
											}
										}
                    $post_array = array(
                        'id' => $id,
                        'title' => $title,
                        'short_title' => $short_title,
                        'link' => $link,
                        'thumb' => $thumb,
                        'desc' => $desc,
                        'price' => $price,
												'product_type' => $product_type,
                    );

										if($product_type == 'auction'){
											$auction_link = get_post_meta(get_the_ID(), 'wpcf-auction-link', false);
											$bid_link = get_post_meta(get_the_ID(), 'wpcf-bid-link', false);
											if(isset($bid_link)){
												$auction_title = '<a href="' . $bid_link[0] . '" >' . strval($title) . '</a>';

												$post_array = array(
		                        'id' => $id,
		                        'title' => $auction_title,
		                        'short_title' => $short_title,
		                        'link' => $bid_link[0],
														'bid_link' => $bid_link[0],
		                        'thumb' => $thumb,
		                        'desc' => $desc,
		                        'price' => $price,
														'product_type' => $product_type,
		                    );
											}
										}
                }
                else {
                    $post_array = array(
                        'id' => $id,
                        'title' => $title,
                        'short_title' => $short_title,
                        'link' => $link,
                        'thumb' => $thumb,
                        'desc' => $desc,
                        'price' => ''
                    );
                }
              array_push($posts_arr, $post_array);
							$log_string .= aw_logMessageStr("End Post Query");
            }
						$log_string .= aw_logMessageStr("End if has posts");
        }
        return $posts_arr;
    }
		function getSKUQuery(string $search_term, array $search_category)
		{
			$log_string = aw_logMessageStr("Start processing '{$search_term}' in category '{$search_category}'");
			$products = wc_get_products(array('sku' => $search_term, 'limit' => 100, 'status' => array('publish'), 'category' => $search_cateogry));
			$results = NULL;
			$posts_arr = array();
			foreach($products as $product){
				$log_string .= aw_logMessageStr("Process data");
					$id = $product->get_id();
					$title = $product->get_name();
					$short_title = $title;
					$length = strlen(strval($title));
					if($length > 100){
							$short_title = substr( $title , 0, 101);
							$short_title .= '...';
					}
					$link = $product->get_permalink();
					$thumb = $product->get_image();
					if($thumb == false || $thumb == null){
						$image_id  = $product->get_image_id();
						$thumb = wp_get_attachment_image_url( $image_id, 'thumbnail' );
						if($thumb == false || $thumb == null){
							$thumb = home_url() . '/wp-content/uploads/2019/12/Acker_Bottle.png';
						}
					}
					$desc = get_the_excerpt($id);
					$price = $product->get_price_html();
					$product_type = 'retail';
					if($search_category == 'auction'){ $product_type = 'auction'; }
					if($search_category == 'retail'){ $product_type = 'retail'; }
					if($search_category == 'auction-retail'){
						$product_cats = wp_get_post_terms( $product->id, 'product_cat', array('orderby' => 'slug') );
						$product_type = $product_cats[0]->slug;
					}
					$price = $product->get_price_html();
					$auction_id = get_post_meta( $id, '_auction_id', true );
					$lot_id = get_post_meta( $id, '_lot_id', true );
					$auction_link = get_post_meta( $id, '_auction_link', true );
					$lot_link = get_post_meta( $id, '_lot_link', true );
					$bid_link = get_post_meta( $id, '_bid_link', true );
					$post_array = array(
							'id' => $id,
							'title' => $title,
							'short_title' => $short_title,
							'product_type' => $product_type,
							'link' => $link,
							'thumb' => $thumb,
							'desc' => $desc,
							'price' => $price,
							'auction_id' => $auction_id,
							'lot_id' => $lot_id,
							'auction_link' => $auction_link,
							'lot_link' => $lot_link,
							'bid_link' => $bid_link
					);
				array_push($posts_arr, $post_array);
				$log_string .= aw_logMessageStr("End Post Query");
			}
			$log_string .= aw_logMessageStr("End if has posts");
			return $posts_arr;
		}

		// function skuSearch(string $search_term)
		// {
		// 	$aw_mysql_conn = aw_mysqlDbConnect();
		// 	if(!$aw_mysql_conn){
		// 		aw_logMessage("couldnt connect to database");
		// 		return "couldnt connect to database";
		// 	}
		// 	else {
		// 		$rows = aw_mysqlGetArray($aw_mysql_conn, "SELECT post_id
		// 			FROM wp_postmeta WHERE meta_key='_sku' AND meta_value LIKE '%{$search_term}%'" );
		// 		$posts = array();
		// 		foreach($rows as $post){
		// 			$post_id = $post['post_id'];
		// 			array_push($posts, $post_id);
		// 		}
		// 		return $posts;
		// 	}
		//
		// }

		// function getWCProducts(string $search_term)
		// {
		// 	$products = wc_get_products(array('sku' => $search_term));
		// 	return $products;
		//
		// }
    function getAuctions(string $search_term, string $search_category, int $post_count)
    {
			$sku_search = $this->getSKUQuery($search_term, array('auction'));
			$product_search = $this->getWPQuery($search_term, $search_category, $post_count);
			if(empty($sku_search)){ return $product_search; }
			if(empty($product_search)){ return $sku_search; }
			if(!empty($sku_search) && !empty($product_search)){
				$results = array_merge($product_search, $sku_search);
				return $results;
			}

    }
    function getRetail(string $search_term, string $search_category, int $post_count)
    {
			$sku_search = $this->getSKUQuery($search_term, array('retail'));
			$product_search = $this->getWPQuery($search_term, $search_category, $post_count);
			if(empty($sku_search)){ return $product_search; }
			if(empty($product_search)){ return $sku_search; }
			if(!empty($sku_search) && !empty($product_search)){
				$results = array_merge($product_search, $sku_search);
				return $results;
			}
    }
		function getAuctionPlusRetail(string $search_term, string $search_category, int $post_count)
    {
			$sku_search = $this->getSKUQuery($search_term, array('auction', 'retail'));
			$auction_search = $this->getWPQuery($search_term, 'auction', $post_count);
			$retail_search = $this->getWPQuery($search_term, 'product', $post_count);

			if(empty($auction_search) && empty($retail_search)){ return $product_search; }
			if(empty($sku_search)){
				$results = array_merge($retail_search, $auction_search);
				return $results;
			}
			if(!empty($sku_search) && !empty($retail_search) && !empty($auction_search)){
				$results = array_merge($retail_search, $auction_search, $sku_search);
				return $results;
			}
    }
    function getPosts(string $search_term, string $search_category, int $post_count)
    {
        $query = $this->getWPQuery($search_term, $search_category, $post_count);
        return $query;
    }
    // function getAllCategories(string $search_term, string $search_category, int $post_count)
    // {
    //     $query_array = array();
    //     foreach(SearchQuery::$optionArr as $i => $cat){
    //         $query;
    //         switch($i){
    //             case 0:
    //                 $query = $this->getAuctions($search_term, $cat, $post_count);
    //                 break;
    //             case 1:
    //                 $query = $this->getRetail($search_term, $cat, $post_count);
    //                 break;
		// 						case 2:
		// 								$query = $this->getAuctionPlusRetail($search_term, $cat, $post_count);
		// 								break;
    //             case ($i > 2):
    //                 $query = $this->getPosts($search_term, $cat, $post_count);
    //                 break;
    //         }
    //         if($cat == 'tasting-notes'){
    //             $cat = 'tastings';
    //         }
    //         $posts_arr = array(
    //             $cat => $query
    //         );
    //         array_push($query_array, $posts_arr);
    //     }
    //     return $query_array;
    // }
    function getResults(string $search_term, string $search_category, int $post_count)
    {
				$start_time = aw_getMicroTime();
				$search_file_log = ABSPATH . 'wp-content' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'acker-wines' . DIRECTORY_SEPARATOR . 'search_terms_cache' . DIRECTORY_SEPARATOR . 'search-' . $search_term . '.log';
				$log_string = aw_logMessageStr("Starting Search");
        $results = NULL; $count = 0;
				$auction_query; $auction_products_query; $retail_query; $event_query; $tastings_query; $faq_query;
        // switch($search_category){
        //     case 'auction':
				// 				$log_string .= aw_logMessageStr("Start processing in category 'auction'");
				// 				$auction_query = $this->getAuctions($search_term, 'auction', $post_count);
        //         break;
				// 		case 'auction-retail':
				// 				$log_string .= aw_logMessageStr("Start processing in category 'auction-retail'");
				// 				$auction_products_query = $this->getAuctionPlusRetail($search_term, 'auction-retail', $post_count);
				// 				break;
        //     case 'product':
				// 				$log_string .= aw_logMessageStr("Start processing in category 'product'");
				// 				$retail_query = $this->getRetail($search_term, 'product', $post_count);
        //         break;
        //     case 'event':
				// 				$log_string .= aw_logMessageStr("Start processing in category 'event'");
				// 				$event_query = $this->getPosts($search_term, 'event', $post_count);
        //         break;
        //     case 'tastings':
				// 				$log_string .= aw_logMessageStr("Start processing in category 'tastings'");
				// 				$tastings_query = $this->getPosts($search_term, 'tastings', $post_count);
        //         break;
        //     case 'faq':
				// 				$log_string .= aw_logMessageStr("Start processing in category 'faq'");
				// 				$faq_query = $this->getPosts($search_term, 'faq', $post_count);
        //         break;
        // }
				if($search_category == 'auction'){
					$log_string .= aw_logMessageStr("Start processing in category 'auction'");
					$auction_query = $this->getAuctions($search_term, 'auction', $post_count);
					$count = count($auction_query);
				}
				if($search_category == 'auction-retail' || $search_category == 'all'){
					$log_string .= aw_logMessageStr("Start processing in category 'auction-retail'");
					$auction_products_query = $this->getAuctionPlusRetail($search_term, 'auction-retail', $post_count);
					$count = count($auction_products_query);
				}
				if($search_category == 'product'){
					$log_string .= aw_logMessageStr("Start processing in category 'product'");
					$retail_query = $this->getRetail($search_term, 'product', $post_count);
					$count = count($retail_query);
				}
				if($search_category == 'event'){
					$log_string .= aw_logMessageStr("Start processing in category 'event'");
					$event_query = $this->getPosts($search_term, 'event', $post_count);
					$count = count($event_query);
				}
				if($search_category == 'tastings'){
					$log_string .= aw_logMessageStr("Start processing in category 'tastings'");
					$tastings_query = $this->getPosts($search_term, 'tastings', $post_count);
					$count = count($tastings_query);
				}
				if($search_category == 'faq'){
					$log_string .= aw_logMessageStr("Start processing in category 'faq'");
					$faq_query = $this->getPosts($search_term, 'faq', $post_count);
					$count = count($faq_query);
				}
				array_push($query_args, array('count' => $count));
				$log_string .= aw_logMessageStr("Found '{$count}' posts in category '{$search_category}'");
				$log_string .= aw_logMessageStr("Finish processing '{$search_term}' in category '{$search_category}'");

				$query_args = array(
					'term' => $search_term,
					'category' => $search_category,
					'count'  => $count,
				);
				$query_arr = array(
					'query_args' => $query_args,
					'posts' => array(
						'auction' => $auction_query,
						'product' => $retail_query,
						'auction_retail' => $auction_products_query,
						'event' => $event_query,
						'tastings' => $tastings_query,
						'faq' => $faq_query
					)
				);
				//
        // //$results = ApiRequestResult::formatResultAsJson($query_array);
				$log_string .= aw_logMessageStr("Write data to file");
				$end_time = aw_getMicroTime();
				$log_string .= aw_logMessageStr("Done searching '{$search_term}' @ '{$end_time}' ");
				file_put_contents($search_file_log, $log_string);

				$results = ApiRequestResult::formatResultAsJson($query_arr);
        return $results;



    }

}
