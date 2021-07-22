<?php

namespace AckerWines;

if ( ! class_exists( 'AW_Wishlist' ) ) {
  class AW_Wishlist {
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

		public function aw_wishlist_get_items()
		{
		$wishlist_data = array();
		if(is_plugin_active('yith-woocommerce-wishlist-premium/init.php')){
			global $yith_wcwl;
	    $yith_wcwl = YITH_WCWL();
			$user = get_current_user_id();
	    $wishlists = YITH_WCWL()->get_wishlists( array( 'user_id' => $user, 'wishlist_visibility' => 'public' ) );
	    $user_meta = get_user_meta( $user );
			if( ! empty( $wishlists ) ){
				$token = $wishlists[0]['wishlist_token'];
		    $wishlist_items = YITH_WCWL()->get_products( $token ? array( 'wishlist_token' => $token ) : array() );
				if(is_array($wishlist_items)){
		    foreach( $wishlist_items as $item ) {
		        global $product, $yith_wcwl_is_wishlist, $yith_wcwl_wishlist_token;
		        $date_added = date_i18n( get_option( 'date_format' ), strtotime( $item['dateadded'] ) );
		        $product = wc_get_product( $item['prod_id'] );
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
						$add_button = !($product->id && $product->is_purchasable()) ? '' : sprintf(
		            '<a href="%s" rel="nofollow" data-product_id="%s" class="aw-button aw-red-button %s product_type_%s">%s</a>',
		            esc_url($product->add_to_cart_url()),
		            esc_attr($product->id),
		            'add_to_cart_button',
		            esc_attr($product->product_type),
		            esc_html($product->add_to_cart_text())
		        );
						$remove_url = home_url() . add_query_arg( 'remove_from_wishlist', $product_id );
						// $remove_button = '<a href="' . esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) . '"
						// class="remove remove_from_wishlist"
						// title="' . apply_filters( 'yith_wcwl_remove_product_wishlist_message_title',__( 'Remove this product', 'yith-woocommerce-wishlist' )) . '">
						// <i class="icon-close"></i></a>';
						$remove_button = '<button class="aw-remove-from-wishlist" product-sku="' . $product_sku . '"  product-id="' . $product_id . '" >
						<i class="fa fa-times-circle"  product-id="' . $product_id . '"  ></i>
						</button>';
						// $in_cart = $this->aw_cart_check_in_cart($product_id);
						// if($in_cart){ $checked = 'icon-check';}
						// 						elseif($stock_status != 'in-stock'){ $checked = 'unavailable'; }
						// else {$checked = 'icon-plus'; }
						// $add_icon = '<a class="button aw-add-to-cart-checkbox"
						// 		data-product_id="' . $product_id . '"
						// 		add_to_cart_button
						// 		' . esc_attr($product->product_type) . '
						//  		href="' .  esc_url($product->add_to_cart_url()) . '"
						// 		rel="nofollow" >
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
							'id' => $product->id,
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
							'remove_url' => $remove_url,
							'remove_button' => $remove_button,
							'product_type' => $product_cat,
						);
						$bid_link; $auction_link; $auction_title;
						if($product_cat === 'auction'){
							$auction_link = get_post_meta(get_the_ID(), 'wpcf-auction-link', false);
							$bid_link = get_post_meta(get_the_ID(), 'wpcf-bid-link', false);
							if(isset($bid_link)){
								$auction_title = '<a href="' . $bid_link[0] . '" >' . strval($title) . '</a>';
								$item_data = array(
									'id' => $product_id,
									'link' => $bid_link,
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
							}
						}
						array_push($wishlist_data, $item_data);
					}//end foreach
					return $wishlist_data;
					}// if is array
				}//if plugin is active
			}//end if wishlist empty
		}// end get wishlist items




	} // end class
} // end if func exists

?>
