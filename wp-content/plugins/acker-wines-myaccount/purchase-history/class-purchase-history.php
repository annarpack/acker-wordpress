<?php

namespace AckerWines;

if ( ! class_exists( 'AW_Purchase_History' ) ) {
  class AW_Purchase_History {

	  const AW_PURCHASE_HISTORY_ENDPOINT = 'purchase-history';

	  public function init(){
			//wp_enqueue_style( 'account-style');
	      // $includes_path = plugin_dir_path( __FILE__ ) . '../includes/';
	      // require_once $includes_path . 'ApiHelper.php';
	      // require_once $includes_path . 'MyAuctionsApi.php';
	      //ApiHelper::registerAjaxEndpoint('aw_purchase_history_filter_click', true);
	      //add_shortcode('aw_purchase_history_filter', array( $this, 'aw_purchase_history_filter_shortcode'));
	      //aw_shared_plugin_init();
	  }
		public static function aw_purchase_history_get_filters(){
				$slugs = ['all', 'shop_order', 'auction', 'tickets', 'shop_subscription'];
				$titles = ['All', 'Retail', 'Auction', 'Tickets', 'Wine Club'];
				$filters = array(
						'slugs' => $slugs,
						'titles' => $titles
				);
				return $filters;
		}
		public static function aw_purchase_history_get_order_type($order_type){
				$order_type_array = ['shop_order', 'shop_subscription', 'tickets', 'auction'];
				$order_titles_array = ['Retail', 'Wine Club', 'Wine Workshop', 'Auction'];
				$index = array_search($order_type, $order_type_array);
				$type_title = $order_titles_array[$index];
				return $type_title;
		}

		public static function aw_purchase_history_filter_buttons(){
				$filters = AW_Purchase_History::aw_purchase_history_get_filters();
				$filter_slugs = $filters['slugs'];
				$filter_titles = $filters['titles'];
				$length = sizeof($filter_titles);
				echo '<div id="purchase-history-filters-section" style="display: none;">
					<div class="aw-type">
						<label>Type</label>
						<input type="hidden" id="purchase-history-filter-selection-type" value="" />
						<select class="type-filter">';
							for($i = 0; $i < sizeof($filter_slugs); $i++){
									echo '<option data-id="' . $filter_slugs[$i] . '" value="' . $filter_titles[$i] . '"  >';
										echo $filter_titles[$i];
									echo '</option>';
								}
						echo '</select>
					</div>

					<div class="aw-dates">
						<label>From</label>
						<input type="text" id="purchase-history-filter-selection-dates-from" value=""  />
						<label>To</label>
						<input type="text" id="purchase-history-filter-selection-dates-to" value=""  />
					</div>

				</div>';

				// <div class="aw-year">
				// 	<label>Year</label>
				// 	<input type="hidden" id="purchase-history-filter-selection-year" value=""  />
				// 	<select class="year-filter">
				// 		<option>All</option>
				// 	</select>
				// </div>
		}

		public static function aw_purchase_history_get_all_auction_orders($data){
			$auction_data = json_decode($data);
			$auction_orders = $auction_data->data;
      $all_orders = array(); $order_data = array();
			foreach($auction_orders as $order){
				$location = $order->siteCode;
				if($location == 'US' || $location == 'NY' || $location == 'Web') {
					$total_due = $order->totalAmount;
					$currancy = '$';
					$interest_amt = $order->interestAmount;
					$balanceDue = $order->balanceDue;
					$paidAmount = $order->paidAmount;
				}
				else {
					$total_due = $order->totalAmountLocal;
					$currancy = $order->currencySymbolLocal;
					$interest_amt = $order->interestAmountLocal;
					$balanceDue = $order->balanceDueLocal;
					$paidAmount = $order->paidAmountLocal;
				}

				$order_total_format = number_format($total_due, 2, '.', ',');
				//$order_total_display = '<span class="woocommerce-Price-amount amount">' . $currancy . ' ' . $order_total_format . ' </span>';
				$order_total_display = $currancy . ' ' . $order_total_format;
				if($balanceDue < 1){
					$payment_status = '<b>PAID</b>';
					$order_status = 'paid';
				}
				else {
					$payment_status = '<b>UNPAID</b>';
					$order_status = 'unpaid';
				}
				$order_type = 'Auction';
				$order_date = $order->auctionDate;
				//$auction_timezone = $order->auctionDate->timezone;
				//$order_date = new \DateTime($auction_date);
				//$order_date = date_format($order_date_data, 'Y-m-d');
				$paddle_id = $order->paddleId;
				$auction_no = $order->auctionNumber;
				$order_number = $auction_no . '-' . $paddle_id;
				//$order_number_display = '<span class="woocommerce-Price-amount amount"> ' . $order_number . ' </span>';
				// $download_button = '<button
				// 		data-paddleID="' . $order_number . '"
				// 		class="aw-invoice-download-button aw-button aw-red-button">Download</button>
				// 		<i class="fa fa-spinner fa-spin"></i>';
				$url = home_url() . '/api/auction-invoice.php?paddle=' . $paddle_id;
				$download_button = '<a class="aw-invoice-download-button aw-button aw-red-button"
					data-paddleID="' . $order_number . '"
					href="' . $url . '" >Download</a>
					<i class="fa fa-spinner fa-spin"></i>';

				$order_data = array(
						'order_date' => $order_date,
						'order_number' => $order_number,
						'order_link' => $order_number,
						'order_type' => $order_type,
						'order_total' => $order_total_display,
						'order_status' => $order_status,
						'payment_status' => $payment_status,
						'ship_status' => '',
						'ship_date' => '',
						'tracking_link' => '',
						'order_details' => $download_button
				);
				array_push($all_orders, $order_data);
			}
			return $all_orders;
		}

	  public static function aw_purchase_history_get_all_retail_orders(){
      $all_orders = array(); $order_data = array();
      $current_user = wp_get_current_user();
      $user = $atts['user'] ? $atts['user'] : get_current_user_id();
      $customer_orders = get_posts(array(
          'numberposts' => -1,
          'meta_key'=> '_customer_user',
          'meta_value'=> $user,
          'post_type' => wc_get_order_types(),
          'post_status' => array_keys(wc_get_order_statuses()),
      ));
      if ($customer_orders) {
        foreach($customer_orders as $customer_order){
            $order = wc_get_order($customer_order);
            $order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
                if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
                    continue;
                }
                $order_url = $order->get_view_order_url();
                $order_number = $order->get_order_number();
                $order_date = $order->get_date_created();
								$order_date = $order_date->date;
								//$order_date = $order_date->date;
								//$order_date = wc_format_datetime($order->get_date_created());
								// $date = $order->get_date_created();
								// $date = $date->date;
								// $order_date_data = new \DateTime($date);
								//$order_date = $order_date_data->format('Y-m-d');
                //$order_total = $order->get_formatted_order_total();
								$order_total = '$' . $order->get_total();
                $item_count = $order->get_item_count();
                $order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
                $order_id = $order->get_order_number();
                $order_url = $order->get_view_order_url();
                $order_number = $order->get_order_number();
                //$order_date = wc_format_datetime($order->get_date_created());
								$date = $order->get_date_created();
								$order_date_data = new \DateTime($date);
								$order_date = date_format($order_date_data, 'Y-m-d');
                $order_status = $order->get_status();
                $order_type = $order->get_type();
								$order_link = '<a href="' . $order_url . '" >' . $order_number . '</a>';
								$details_button = !($order_id) ? '' : sprintf(
   	                '<a href="%s" rel="nofollow" data-order_id="%s" class="aw-button aw-red-button %s ">%s</a>',
   	                esc_url($order_url),
   	                esc_attr($order_id),
   	                'order_details_button',
   	                'Details'
   	            );
								$pay_url = esc_url($order->get_checkout_payment_url());
 							 	$pay_link = '<button class="aw-button aw-red-button" href="' . $pay_url . '" >Pay Now</button>';
                $payment_status;
                if($order_status == 'completed' || $order_status == 'processing'){
                  $payment_status = '<b>PAID</b>';
                }
                else if($order_status == 'pending' || $order_status == 'on-hold'){
                    //$payment_status = '<b>UNPAID</b>';
									$payment_status = '<a href="' . $pay_url . '" class="aw-button aw-red-button pay"" >Pay Now</a>';
                }
								else if($order_status == 'cancelled'){
									$payment_status = '<b>CANCELLED</b>';
								}
                $ship_status;
                if($order_status == 'completed'){
									$payment_status = '<b>PAID</b>';
                  $ship_status = 'Shipped';
                }
                else if($order_status == 'processing' ){
									$payment_status = '<a href="' . $pay_url . '" class="aw-button aw-red-button pay"" >Pay Now</a>';
                  $ship_status = 'To Be Shipped';
                }
                else { $ship_status = 'Not Shipped'; }
                $ship_date = ''; $tracking_link = '';
                if( class_exists('WC_Shipment_Tracking_Actions')){
                    $st = \WC_Shipment_Tracking_Actions::get_instance();
                    if(isset($st)){
                        $tracking_items = $st->get_tracking_items( $order_id );
                        if(($order_status == 'completed') && isset($tracking_items)) {
                            $tracking_item = $tracking_items[0];
                            if( isset($tracking_item['date_shipped'])){
															if($ship_status == 'Shipped'){
																$ship_status = '<time datetime=" ' . date( 'Y-m-d', $tracking_item['date_shipped'] ) . ' " title="' .  date( 'Y-m-d', $tracking_item['date_shipped'] ) . ' "> Shipped ' .  date_i18n( get_option( 'date_format' ), $tracking_item['date_shipped'] ) . '</time></br>';
															}
														} //end if
														if(isset($tracking_item['tracking_provider'])){
															$provider = $tracking_item['tracking_provider'];
															switch($provider){
																case "ups":
																	$tracking_link = 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=1Z';
																	break;
																case "fedex":
																	$tracking_link = 'http://www.fedex.com/Tracking?action=track&tracknumbers=';
																	break;
																case "usps":
																	$tracking_link = 'https://tools.usps.com/go/TrackConfirmAction?tLabels=';
																	break;
																case "lasership":
																	$tracking_link = 'http://www.lasership.com/track/LS';
																	break;
															}
														}
														if(isset($tracking_item['tracking_number'])){
															$tracking_link = $tracking_link . $tracking_item['tracking_number'];
															$tracking_link = '<a href="' . $tracking_link . '" class=" aw-button aw-red-button order_details_button" >Track</a>';
														}
                        }//end if
                    }
                }
                if(metadata_exists('post', $order_id, '_eventid')){
                    $tickets = get_post_meta( $order_id , '_eventid', true);
                    if($tickets != ''){
                        $order_type = 'Wine Workshop';
                    }
                }
								$order_type_title = AW_Purchase_History::aw_purchase_history_get_order_type($order_type);
                $item_data = array(
                    'order_date' => $order_date,
                    'order_number' => $order_number,
                    'order_link' => $order_link,
                    'order_type' => $order_type_title,
                    'order_total' => $order_total,
                    'order_status' => $order_status,
                    'payment_status' => $payment_status,
                    'ship_status' => $ship_status,
										'ship_date' => $ship_date,
                    'tracking_link' => $tracking_link,
										'order_details' => $details_button,
										'pay_link' => $pay_link
                );
            array_push($order_data, $item_data);
        }//end for each order
          //array_push($all_orders, $order_data);
      }//end if customer orders
      //return json_encode($order_data);
      return $order_data;
	  }


		public static function aw_purchase_history_get_combined_data(){
			$retail_results = AW_Purchase_History::aw_purchase_history_get_all_retail_orders();
			$auction_api = new API\AuctionProgApi();
			$auction_data = $auction_api->getInvoices($auction_api->getApcId());
			if(isset($auction_data)){
				if(!empty($auction_data)){
					$auction_results = AW_Purchase_History::aw_purchase_history_get_all_auction_orders($auction_data);
					$all_results = array_merge($retail_results, $auction_results);
					return $all_results;
				}
				else {
					return $retail_results;
				}
			}
			else {
			 	return $retail_results;
			}
		}

  } // end class defninition
} // end if
