<?php

namespace AckerWines;

if ( ! class_exists( 'AW_Favorites' ) ) {
  class AW_Favorites {
		public function aw_cart_check_in_cart($product_id){
			global $woocommerce;
				foreach($woocommerce->cart->get_cart() as $key => $val ) {
						$_product = $val['data'];
						if($product_id == $_product->id ) {
								return true;
						}
				}
				return false;
		}
		public function aw_favorites_get_items()
		{
		$favorites_data = array();
		if(is_plugin_active('ti-woocommerce-wishlist/ti-woocommerce-wishlist.php')){
			$user = get_current_user_id();
			$user_meta = get_user_meta( $user );
			$favorites_list = tinv_wishlist_get();
			$fav_id = $favorites_list['ID'];
	    $is_owner = $favorites_list['is_owner'];
			if( ! empty( $favorites_list ) && $is_owner == true ){
				$favorites_items = tinvwl_get_wishlist_products( $fav_id );
				if(is_array($favorites_items)){
					foreach( $favorites_items as $item ) {
						global $product;
						//$date_added = date_i18n( get_option( 'date_format' ), strtotime( $item['dateadded'] ) );
						$product = wc_get_product( $item['product_id'] );
						$product_id = $product->id;
						$product_sku = $product->get_sku();
		        $availability = $product->get_availability();
		        $stock_status = isset( $availability['class'] ) ? $availability['class'] : false;
		        $product_data = $product->get_data();
		        $product_name = $product->get_name();
		        $current_price = $product->get_price_html();
		        $product_total = wc_price($product->get_price());
		        $is_visible = $product && $product->is_visible();
		        $product_permalink = get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ));
						$product_link = esc_url( get_permalink( $product_id ));
						$add_button = '<a href="' .  esc_url($product->add_to_cart_url()) . '" rel="nofollow"
								data-product_id="' . esc_attr($product_id) . '"
								class="aw-button aw-red-button ">' . esc_html($product->add_to_cart_text()) . ' </a>';
						// $remove_button = '<button type="submit" name="tinvwl-remove"
				    //     value="' .  $product_id . '"
				    //     title="Remove">
						// 		<i class="icon-close"></i>
						// </button>';
						// $remove_button = '<button class="aw-remove-from-wishlist" product-id="' . $product_id . '"  >
						// <i class="ftinvwl ftinvwl-times" product-sku="' . $product_sku . '"  product-id="' . $product_id . '"  ></i>
						// </button>';
						$remove_button =
						'<ul class="woo-entry-buttons">
						 <li class="woo-wishlist-btn">
							 <div class="tinv-wraper woocommerce tinv-wishlist tinvwl-shortcode-add-to-cart" product-id="' . $product_id . '">
								 <button class="aw-remove-from-wishlist" :product-id="product.id">
									 <a href="javascript:void(0)" class="tinvwl_add_to_wishlist_button tinvwl-icon-heart no-txt  tinvwl-position-shortcode inited-add-wishlist tinvwl-product-in-list tinvwl-product-make-remove"
									 product-sku="' . $product_sku . '"  product-id="' . $product_id . '"
									 rel="nofollow"
									 >
								 </a>
							 </button>
						 </div>
						 </li>
					 </ul>';

						//$in_cart = $this->aw_cart_check_in_cart($product_id);
						// if($in_cart){ $checked = 'icon-check';}
						// elseif($stock_status != 'in-stock'){ $unavailable = 'unavailable'; $checked = ''; }
						// else {$checked = 'icon-plus'; }
						// $add_icon = '<a class="button aw-add-to-cart-checkbox"
						// 		data-product_id="' . $product_id . '"
						// 		add_to_cart_button
						// 		' . esc_attr($product->product_type) . '
						// 		href="' .  esc_url($product->add_to_cart_url()) . '"
						// 		rel="nofollow" >
						// 			' . $unavailable . '
						// 			<i class=" ' . $checked . '"  data-id="' . $product_id . '"  ></i>
						// 		</a>';
						$product_cats = wp_get_post_terms( $product->id, 'product_cat', array('orderby' => 'slug') );
						$product_cat = $product_cats[0]->slug;

						$thumb_link = get_the_post_thumbnail_url($product->id);
						if($thumb_link == 'false' || $thumb_link == false || $thumb_link == NULL){ $thumb_link = 'https://ackerwines.co/webimages/wpimages/logo.jpg'; }
						$built_link = '<a href="' . $product_link . '" >' . $product->get_title() . '</a>';
						$attributes = (array) $product->get_attributes();
						$pa_format_term = $attributes['pa_bottle-size']['data']['options'][0];
						$pa_format = get_term($pa_format_term);
						if(isset($pa_format)){
							$pa_format = $pa_format->name;
						}
						$pa_producer_term = $attributes['pa_producer']['data']['options'][0];
						$pa_producer = get_term($pa_producer_term);
						if(isset($pa_producer)){
							$pa_producer = $pa_producer->name;
						}
						$pa_designation_term = $attributes['pa_vineyard']['data']['options'][0];
						$pa_designation = get_term($pa_designation_term);
						if(isset($pa_designation)){
							$pa_designation = $pa_designation->name;
						}
						$pa_vintage_term = $attributes['pa_vintage']['data']['options'][0];
						$pa_vintage = get_term($pa_vintage_term);
						if(isset($pa_vintage)){
							$pa_vintage = $pa_vintage->name;
						}

						$item_data = array(
							'id' => $product_id,
							'link' => $product_link,
							'title' => $product_name,
							'thumb' => $thumb_link,
							'sku' => $product_sku,
							'format' => $pa_format,
							'vintage' => $pa_vintage,
							'name_link' => $built_link,
							'designation' => $pa_designation,
							'producer' => $pa_producer,
							'cart_button' => $add_button,
							'remove_button' => $remove_button,
							'product_type' => $product_cat,
						);

						$bid_link; $auction_link; $auction_title;
						if($product_cat === 'auction'){
							$auction_link = get_post_meta(get_the_ID(), 'wpcf-auction-link', false);
							$bid_link = get_post_meta(get_the_ID(), 'wpcf-bid-link', false);
							if(isset($bid_link)){
								$auction_title = '<a href="' . $bid_link[0] . '" >' . strval($product_name) . '</a>';
								$item_data = array(
									'id' => $product_id,
									'link' => $bid_link,
									'title' => $auction_title,
									'thumb' => $thumb_link,
									'sku' => $product_sku,
									'format' => $pa_format,
									'vintage' => $pa_vintage,
									'name_link' => $built_link,
									'designation' => $pa_designation,
									'producer' => $pa_producer,
									'cart_button' => $add_button,
									'remove_button' => $remove_button,
									'product_type' => $product_cat,
								);
							}
						}
						array_push($favorites_data, $item_data);
					}//end foreach
					return $favorites_data;
				}//if array
				}//end if favorite empty
			} // if plugin is active
		}// end get favorite items

		public static function aw_favorites_remove_item(){
			global $wpdb;
			$product_id = $_POST['product_id'];
			$wishlist_id = $_POST['wishlist_id'];
			$user = get_current_user_id();
			$user_meta = get_user_meta( $user );
			$favorites_list = tinv_wishlist_get();
			$wishlist_id = $favorites_list['ID'];
			$variation_id = $favorites_list['variation_id'];
	    $is_owner = $favorites_list['is_owner'];
			$response = array($product_id, $wishlist_id);
			if(class_exists('TInvWL_Product')){

				//$sql_data = 'SELECT product_id FROM wp_tinvwl_items WHERE author = ' . $user;
				//$sql_data = 'SELECT * FROM wp_tinvwl_items WHERE author = ' . $user . ' AND product_id = ' . $product_id;
				//$result = $wpdb->query($sql_data);

				$sql_data = 'DELETE FROM wp_tinvwl_items WHERE wishlist_id = %d AND product_id = %d';
				$prep = $wpdb->prepare($sql_data, array('wishlist_id' => $wishlist_id, 'product_id' => $product_id ));
				$result = $wpdb->delete( 'wp_tinvwl_items', array( 'wishlist_id' => $wishlist_id, 'product_id' => $product_id ) );

				if ( $result ) {
					do_action( 'tinvwl_wishlist_product_removed_from_wishlist', $wishlist_id, $product_id, 0 );
				}

				//$res = \TInvWL_Product::remove_product_from_wl( $wishlist_id, $product_id );
			}
			else { $result = false; }

			return $result;
		}

	} // end class
} // end if func exists

?>
