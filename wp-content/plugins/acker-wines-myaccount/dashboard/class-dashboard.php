<?php
namespace AckerWines;
use AckerWines\Api\AckerApi;
use AckerWines\Api\AuctionProgApi;

if ( ! class_exists( 'AW_Dashboard' ) ) {
  class AW_Dashboard {

		public static function aw_dashboard_get_order_type($order_type){
				$order_type_array = ['shop_order', 'shop_subscription', 'tickets', 'auction'];
				$order_titles_array = ['Retail', 'Wine Club', 'Wine Workshop', 'Auction'];
				$index = array_search($order_type, $order_type_array);
				$type_title = $order_titles_array[$index];
				return $type_title;
		}

		public static function aw_dashboard_get_all_auction_orders($data){
			// blanceDue = total + interest - amountPaid
			// totalAmount = original invoice subtotal without interest or amount paid

			$pending = array(); $recent = array(); $processing = array(); $unpaid = array(); $shipped = array(); $payments = array();
			$auction_data = json_decode($data);
			$auction_orders = $auction_data->data;
      $all_orders = array(); $order_data = array();	$completed = false;
			$i = 0;
			foreach($auction_orders as $order){
				$order_type = 'Auction';
				$order_date = $order->auctionDate;
				$location = $order->siteCode;
				if($location == 'US' || $location == 'NY' || $location == 'Web'){
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
				$total_due_formatted = number_format($total_due, 2, '.' , ',');
				$total_due_display = $currancy . ' ' . $total_due_formatted;
				//$balance_due_formatted = number_format($balanceDue, 2, '.' , 3);
				$balance_due_formatted = number_format($balanceDue, 2, '.' , ',');
				$balance_due_display = $currancy . ' ' . $balance_due_formatted;
				$interest_amt_formatted = number_format($interest_amt, 2, '.' , ',');
				$interest_amt_display = $currancy . ' ' . $interest_amt_formatted;
				$next_int_date = $order->nextInterestDate;
				$due_date = $order->dueDate;
				//$order_number = $order->paddleId;
				$paddle_id = $order->paddleId;
				$auction_no = $order->auctionNumber;
				$order_number = $auction_no . '-' . $paddle_id;
				// $download_button = '<button
				// 		data-paddleID="' . $order_number . '"
				// 		class="aw-button aw-red-button aw-invoice-download-button">Download</button>
				// 		<i class="fa fa-spinner fa-spin"></i>';
				$url = home_url() . '/api/auction-invoice.php?paddle=' . $paddle_id;
				$download_button = '<a class="aw-invoice-download-button aw-button aw-red-button"
					target="_new"
					data-paddleID="' . $order_number . '"
					href="' . $url . '" >Download</a>
					<i class="fa fa-spinner fa-spin"></i>';
				$order_status;
				$total_due_num = intval($total_due);
				$consignor = $order->isConsignor;

				if($balanceDue < 1){
					$payment_status = 'PAID';
					$order_status = 'paid';
				}
				else {
					$payment_status = 'UNPAID';
					$order_status = 'unpaid';
				}
				// else {
				// 	$order_status = 'pending';
				// }
				// if($consignor == true){
				// 	$order_status = 'payments';
				// }
				if($i < 3){
					array_push( $recent, array(
						'order_date' => $order_date,
						'order_number' => $order_number,
						//'order_link' => $order_number,
						'order_type' => $order_type,
						'order_total' => $total_due_display,
						'interest' => $interest_amt_display,
						'due_date' => $next_int_date,
						'total_due' => $balance_due_display,
						'order_status' => $order_status,
						'payment_status' => $payment_status,
						'ship_status' => '',
						'ship_date' => '',
						'tracking_link' => '',
						'order_loc' => $location,
						'details_button' => $download_button
					));
				}
				if($order_status == 'unpaid'){
					array_push( $unpaid, array(
						'order_date' => $order_date,
						'order_number' => $order_number,
						//'order_link' => $order_number,
						'order_type' => $order_type,
						'order_total' => $total_due_display,
						'interest' => $interest_amt_display,
						'due_date' => $next_int_date,
						'total_due' => $balance_due_display,
						'order_status' => $order_status,
						'payment_status' => $payment_status,
						'ship_status' => '',
						'ship_date' => '',
						'tracking_link' => '',
						'order_loc' => $location,
						'details_button' => $download_button
					));
				}
				// if($order_status == 'processing'){
				// 	$arr = array(
				// 		'order_date' => $order_date,
				// 		'order_number' => $order_number,
				// 		'order_link' => $order_number,
				// 		'order_type' => $order_type,
				// 		'order_total' => $total_due_display,
				// 		'interest' => $interest_amt_display,
				// 		'due_date' => $next_int_date,
				// 		'total_due' => $balance_due_display,
				// 		'order_status' => $order_status,
				// 		'payment_status' => $payment_status,
				// 		'ship_status' => '',
				// 		'ship_date' => '',
				// 		'tracking_link' => '',
				// 		'details_button' => $download_button
				// 	);
				// 	array_push( $processing, $arr);
				// }
				// if($order_status == 'pending'){
				// 	$arr = array(
				// 		'order_date' => $order_date,
				// 		'order_number' => $order_number,
				// 		'order_link' => $order_number,
				// 		'order_type' => $order_type,
				// 		'order_total' => $total_due_display,
				// 		'interest' => $interest_amt_display,
				// 		'due_date' => $next_int_date,
				// 		'total_due' => $balance_due_display,
				// 		'order_status' => $order_status,
				// 		'payment_status' => $payment_status,
				// 		'ship_status' => '',
				// 		'ship_date' => '',
				// 		'tracking_link' => '',
				// 		'details_button' => $download_button
				// 	);
				// 	array_push( $pending, $arr);
				// }
				if($order_status == 'completed'){
					$completed = true;
				}
				$i++;
				//array_push($all_orders, $order_data);

			}
			$processing = array('processing' => $processing);
			$pending = array('pending' => $pending);
			$unpaid = array('unpaid' => $unpaid);
			$shipped = array('shipped' => $shipped);
			$recent = array('recent' => $recent);
			if($completed == true){
				$completed = array('completed' => 'all auctions are complete');
				$all_orders = array_merge($processing, $pending, $unpaid, $shipped, $recent, $completed);
			}
			else {
				$all_orders = array_merge($processing, $pending, $unpaid, $shipped, $recent );
			}

			return $all_orders;
		}
		public static function aw_dashboard_get_consignment_payments($data){
			$data = json_decode($data);
			$data = $data->data;
			$payments = array(); $first_date = null; $next_date = null;
			foreach($data as $payment){
				if(!empty($payment)){
					$next = $payment->NextPaymentDt;
					if($next != null){
						$auction_no = $payment->AuctionNoSuffix;
						$sale = $payment->SaleDate->date;
						$sale = new \DateTime($sale);
						$sale_date = $sale->format('Y-m-d');
						$next = $payment->NextPaymentDt->date;
						$next = new \DateTime($next);
						$next_date = $next->format('Y-m-d');
						if(property_exists($payment, 'siteCode')){
							$location = $payment->siteCode;
						}
						// if($location == 'HKD'){
						// 	$total_due = $total_due->totalBalanceDueLocal;
						// 	$currancy = 'HK$';
						// }
						// elseif($location == 'USD'){
						// 	$total_due = $total_due->totalBalanceDue;
						// 	$currancy = '$';
						// }
						// else {
						// 	$total_due = $total_due->totalBalanceDue;
						// 	$currancy = '$';
						// }

						array_push( $payments, array(
							'order_date' => $sale_date,
							'release_date' => '',
							'next_payment' => $next_date,
							'order_number' => $auction_no,
							'order_link' => '',
							'order_type' => 'Auction',
							'order_total' => '',
							'interest' => '',
							'due_date' => '',
							'total_due' => '',
							'order_status' => '',
							'payment_status' => '',
							'ship_status' => '',
							'ship_date' => '',
							'tracking_link' => '',
							'details_button' => ''
						));
					}//if first not null
					//echo var_dump($payments);
				}
			} // emd for each
			return $payments;
		}

		public static function aw_dashboard_get_retail_orders_info(){
				$user = get_current_user_id();
  	     $customer_orders = get_posts(array(
  	         'numberposts' => -1,
  	         'meta_key'=> '_customer_user',
  	         'meta_value'=> $user,
  	         'post_type' => wc_get_order_types(),
  	         'post_status' => array_keys(wc_get_order_statuses()),
  	     ));
  	     $pending = array(); $recent = array(); $processing = array(); $unpaid = array(); $shipped = array();
  	     if ($customer_orders) {
  	         $orders_array = array();
						 $i = 0;
						 $location = 'NY';
  	         foreach ($customer_orders as $customer_order) {
							$order = wc_get_order($customer_order);
							 //$order_id = $order->ID;
							$order_url = $order->get_view_order_url();
							$order_number = $order->get_order_number();
							$date = $order->get_date_created();
							$order_date_data = new \DateTime($date);
							$order_date = $order_date_data->format('Y-m-d');
							$order_total = '$' . $order->get_total();
							$order_total_formatted = $order->get_formatted_order_total();
							$order_status = $order->get_status();
							$order_type = $order->get_type();
 							$order_type = AW_Dashboard::aw_dashboard_get_order_type($order_type);
 							$payment_status = $order->is_paid();
 							$order_url = $order->get_view_order_url();
 							$order_link = '<a href="' . $order_url . '" >' . $order_number . '</a>';
							$pay_url = esc_url($order->get_checkout_payment_url());
							$paylink = '<a href="' . $pay_url . '" class="aw-button aw-red-button pay"" >Pay Now</a>';
							$payment_status; $ship_status;
							if($order_status == 'completed'){
									$payment_status = 'PAID';
									$ship_status = 'Shipped';
							}
							elseif($order_status == 'processing' || $order_status == 'pending' ){
									$payment_status = 'UNPAID';
									$ship_status = 'To Be Shipped';
							}
							else { $ship_status = 'Not Shipped'; }

							$ship_date = ''; $tracking_link = '';
							$st = \WC_Shipment_Tracking_Actions::get_instance();
							$tracking_items = $st->get_tracking_items( $order_number );
							 if($order_status == 'completed' && !empty($tracking_items)){
								 $tracking_item = $tracking_items[0];
								 if( isset($tracking_item['date_shipped'])){
									 if($ship_status == 'Shipped'){
										 $ship_status = '<span datetime=" ' . date( 'Y-m-d', $tracking_item['date_shipped'] ) . ' " title="' .  date( 'Y-m-d', $tracking_item['date_shipped'] ) . ' "> Shipped ' .  date_i18n( get_option( 'date_format' ), $tracking_item['date_shipped'] ) . '</span></br>';
									 }
								 } //end if
								 if(isset($tracking_item['tracking_provider'])){
									 $provider = $tracking_item['tracking_provider'];
									 $provider_link;
										 if($provider == "ups"){
											 $provider_link = 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=1Z';
										 }
										 elseif($provider == "fedex"){
											 $provider_link = 'http://www.fedex.com/Tracking?action=track&tracknumbers=';
										 }
										 elseif($provider == "usps"){
											 $provider_link = 'https://tools.usps.com/go/TrackConfirmAction?tLabels=';
										 }
										 elseif($provider == "lasership"){
											 $provider_link = 'http://www.lasership.com/track/LS';
										 }
										 $tracking_link = $provider_link . $tracking_item['tracking_number'];
										 $tracking_link = '<a href="' . $tracking_link . '" class=" aw-button aw-red-button order_details_button" >Track</a>';
								 }
 							 }
  	           $details_button = !($order_number) ? '' : sprintf(
  	                '<a href="%s" rel="nofollow" data-order_id="%s" class=" aw-button aw-red-button %s ">%s</a>',
  	                esc_url($order_url),
  	                esc_attr($order_number),
  	                'order_details_button',
  	                'Details'
  	            );
								if($order_status == 'completed' && !empty($tracking_items) ){
									array_push( $shipped , array(
											'order_id' => $order_number,
											'order_url' => $order_url,
											'order_number' => $order_number,
											'order_date' => $order_date,
											'order_type' => $order_type,
											'interest' => '-',
											'total_due' => $order_total,
											'order_total' => $order_total,
											'order_status' => $order_status,
											'payment_status' => $payment_status,
											'order_type' => $order_type,
											'tracking_link' => $tracking_link,
											'ship_status' => $ship_status,
											'order_link' => $order_link,
											'order_loc' => $location,
											'details_button' => $details_button,
											'pay_link' => $paylink
									));
								}
  	            elseif($order_status == 'processing'){
									array_push( $processing , array(
	   	                'order_id' => $order_number,
	   	                'order_url' => $order_url,
	   	                'order_number' => $order_number,
	   	                'order_date' => $order_date,
	  									'order_type' => $order_type,
											'interest' => '-',
	   	                'total_due' => $order_total,
											'order_total' => $order_total,
	   	                'order_status' => $order_status,
	  									'payment_status' => $payment_status,
	   	                'order_type' => $order_type,
	   	                'tracking_link' => $tracking_link,
											'ship_status' => $ship_status,
	  									'order_link' => $order_link,
											'order_loc' => $location,
	   	                'details_button' => $details_button,
											'pay_link' => $paylink
	   	            ));
								}
  	            elseif($order_status == 'pending'){
									array_push( $pending , array(
											'order_id' => $order_number,
											'order_url' => $order_url,
											'order_number' => $order_number,
											'order_date' => $order_date,
											'order_type' => $order_type,
											'interest' => '-',
											'total_due' => $order_total,
											'order_total' => $order_total,
											'order_status' => $order_status,
											'payment_status' => $payment_status,
											'order_type' => $order_type,
											'tracking_link' => $tracking_link,
											'ship_status' => $ship_status,
											'order_link' => $order_link,
											'order_loc' => $location,
											'details_button' => $details_button,
											'pay_link' => $paylink
									));
								}
 								if($payment_status == 'UNPAID'){
									array_push( $unpaid, array(
											'order_id' => $order_number,
											'order_url' => $order_url,
											'order_number' => $order_number,
											'order_date' => $order_date,
											'order_type' => $order_type,
											'interest' => '-',
											'total_due' => $order_total,
											'order_total' => $order_total,
											'order_status' => $order_status,
											'payment_status' => $payment_status,
											'order_type' => $order_type,
											'tracking_link' => $tracking_link,
											'ship_status' => $ship_status,
											'order_link' => $order_link,
											'order_loc' => $location,
											'details_button' => $details_button,
											'pay_link' => $paylink
									));
								}
								if($i < 3){
									array_push( $recent, array(
										'order_date' => $order_date,
										'order_number' => $order_number,
										'order_link' => $order_number,
										'order_type' => $order_type,
										'total_due' => $order_total,
										'order_total' => $order_total,
										'interest' => '-',
										'order_status' => $order_status,
										'payment_status' => $payment_status,
										'ship_status' => $ship_status,
										'order_link' => $order_link,
										'order_loc' => $location,
										'tracking_link' => $tracking_link,
										'details_button' => $details_button,
										'pay_link' => $paylink
									));
								}
								$i++;
  	        }
  	     }
					$processing = array('processing' => $processing);
					$pending = array('pending' => $pending);
					$unpaid = array('unpaid' => $unpaid);
					$shipped = array('shipped' => $shipped);
					$recent = array('recent' => $recent);
					$all_orders = array_merge($processing, $pending, $unpaid, $shipped, $recent);

  	     return $all_orders;
  	 }


		 public static function aw_dashboard_get_combined_data(){
			 $retail_results = AW_Dashboard::aw_dashboard_get_retail_orders_info();
			 // $today = new \DateTime();
			 // $this_year = date_format($today, 'Y');
			 // $this_year = intval($this_year) - 5;
			 // $searchDate = $this_year . '-01-01';
			 $auction_api = new Api\AuctionProgApi();
			 $apc_id = $auction_api->getApcId();
			 $searchDate = ''; $location = ''; $total_due_display = '';
			 if(!is_int($apc_id)){
				 $apc_id = intval($apc_id);
			 }
			 if(isset($apc_id) && !empty($apc_id)){
				 $auction_data = $auction_api->getInvoices($apc_id);
				 $consign_payments = $auction_api->getConsignorSales(1821, $searchDate);
				 if(isset($auction_data) && !empty($auction_data)){
						 $auction_results = AW_Dashboard::aw_dashboard_get_all_auction_orders($auction_data);
						 $total_due_api = $auction_api->getTotalBalance($apc_id);
						 if(isset($total_due_api)){
								$total_due_data = json_decode($total_due_api);
								if(isset($total_due_data) && is_object($total_due_data)){
								$total_due = $total_due_data->data;
								if(isset($total_due) && ($total_due != null)){
									$total_due = $total_due[0];
									$location = $total_due->currencyCodeLocal;
		 						 if($location == 'HKD'){
		 							 $total_due = $total_due->totalBalanceDueLocal;
		 							 $currancy = 'HK$';
		 						 }
		 						 elseif($location == 'USD'){
		 							 $total_due = $total_due->totalBalanceDue;
		 							 $currancy = '$';
		 						 }
		 						 else {
		 							 $total_due = $total_due->totalBalanceDue;
		 							 $currancy = '$';
		 						 }
		 						 if($total_due < 0 || $total_due == null){
		 							 $total_due = '0';
		 						 }
								 $total_due_display = null;
								 if($total_due != null || $total_due != undefined){
									 $total_due_display = number_format($total_due, 2, '.', ',');
									 $total_due_display = $currancy . ' ' . $total_due_display;
									}
								}

							}
						 }//if total due is set
						 $payments = array();
						 if(isset($consign_payments) && !empty($consign_payments)){
							 $cons_payment_data = AW_Dashboard::aw_dashboard_get_consignment_payments($consign_payments);
							 $payments = $cons_payment_data;
						 }//if consignment payments is set

						 if(!empty($retail_results)){
							 $processing_auction = $auction_results['processing'] ? $auction_results['processing'] :  array();
							 $pending_auction = $auction_results['pending'] ?  $auction_results['pending'] :  array();
							 $unpaid_auction = $auction_results['unpaid'] ? $auction_results['unpaid'] :  array();
							 $shipped_auction = $auction_results['shipped'] ? $auction_results['shipped'] :  array();
							 $processing_retail = $retail_results['processing'] ? $retail_results['processing'] : array();
							 $recent_retail = $retail_results['recent'] ? $retail_results['recent'] : array();
							 $recent_auction = $auction_results['recent'] ? $auction_results['recent'] : array();
							 $pending_retail = $retail_results['pending'] ? $retail_results['pending'] : array();
							 $unpaid_retail = $retail_results['unpaid'] ? $retail_results['unpaid'] : array();
							 $shipped_retail = $retail_results['shipped'] ? $retail_results['shipped'] : array();
							 $unpaid = array_merge($unpaid_auction, $unpaid_retail);
							 $processing = array_merge($processing_auction, $processing_retail);
							 $pending = array_merge($pending_auction, $pending_retail);
							 $shipped = array_merge($shipped_retail, $shipped_auction);
							 $recent = array_merge($recent_retail, $recent_auction);
							 $response_data = array(
								'totalDue' => $total_due,
								'totalDueDisplay' => $total_due_display,
								'order_loc' => $location,
								'unpaid' => $unpaid,
								'processing' => $processing,
								'pending' => $pending,
								'payments' => $payments,
								'recent' => $recent,
								'shipped' => $shipped
							);
							if(in_array('completed', $auction_results)){
						 		$completed = $auction_results['completed'];
								if(isset($completed)){
									if($completed == 'all'){
		 							 $response_data = array(
		 								'totalDue' => $total_due,
		 								'totalDueDisplay' => $total_due_display,
		 								'order_loc' => $location,
		 								'unpaid' => $unpaid,
		 								'processing' => $processing,
		 								'pending' => $pending,
		 								'payments' => $payments,
		 								'shipped' => $shipped,
		 								'recent' => $recent,
		 								'auctions' => 'all complete'
		 							);
								}}
							}
					 }// end if retail results are NOT empty
					 else {
						 $response_data = array(
							'totalDue' => $total_due,
							'totalDueDisplay' => $total_due_display,
							'order_loc' => $location,
							'unpaid' => $unpaid_auction,
							'processing' => $processing_auction,
							'pending' => $pending_auction,
							'payments' => $payments,
							'recent' => $recent_auction,
							'shipped' => $shipped_auction
						);
						return $response_data;
					} // end if retail results are empty

					 return $response_data;
					 //return $consign_payments;
				 }
			}
			elseif(!empty($retail_results)){
				return $retail_results;
			}
			else {
				//array('you have no orders');
				return array();
			}
		 }


		public static function aw_dashboard_get_notifications(){
			$user = get_current_user_id();
			if( wc_has_notice($user) == true){
					//echo '<h3>' . $notice . "</h3>";
			}
			echo '<div id="aw-myaccount-notifications">';
			$orders_array = AW_Dashboard::aw_dashboard_get_combined_data();
			$processing_arr = $orders_array['processing'];
			$pending_arr = $orders_array['pending'];
			$unpaid_arr = $orders_array['unpaid'];
			$shipped_arr = $orders_array['shipped'];
			if(in_array('totalDue', $orders_array)){
				$total_due = $orders_array['totalDue'];
			}
			$processing = is_array($processing_arr) ? count($processing_arr) : 0;
			$pending = is_array($pending_arr) ? count($pending_arr) : 0;
			$unpaid = is_array($unpaid_arr) ? count($unpaid_arr) : 0;
			$shipped = is_array($shipped_arr) ? count($shipped_arr) : 0;
			$upcoming_payments = 'N/A';
			$dashboard_url = home_url() . '/my-account/';
			if($unpaid > 0){
					 echo '<p class="alert-message"><a href="' . $dashboard_url . '" >(' . $unpaid . ') Payments Due </p></a>';
		 }
		 if($shipped > 0){
					echo '<p class="alert-message"><a href="' . $dashboard_url . '" >Shipment Pending </p></a>';
			}
			if($processing > 0){
					echo '<p class="alert-message"><a href="' . $dashboard_url . '" >(' . $processing . ') Processing Orders </p></a>';
			}
			if($pending > 0){
					echo '<p class="alert-message"><a href="' . $dashboard_url . '" >(' . $pending . ') Pending Orders </p></a>';
			}
			echo '</div>';

		}


 	 public static function aw_dashboard_get_content() {
 	     $user = get_current_user_id();
 	     ?>
 	     <div id="my-dashboard">
 	         <?php AW_Dashboard::aw_dashboard_get_sections_content($user); ?>
 	     </div>
 	     <?php

 	 } // end func my dashboard content

	 public static function aw_appraisal_table(){
		 echo '<div class="appraisal-submit-button-dashboard">
			 <button id="appraisal-submit-button" class="aw-button aw-button-red">
				 Submit an Appraisal
			 </button>
		 <div id="appraisal-submit-form">';
			 echo do_shortcode('[contact-form-7 id="47302" title="Appraisal Upload"]');
		 echo '</div></div>';
	 }

	 public static function aw_dashboard_get_template(){
		 include_once(plugin_dir_path( __FILE__ ) . '../page-loading.php');
 	 ?>
		<div id="dashboard-vue-root" style="display:none;">
			<div class="aw-total-due" v-if="orders.totalDue !== undefined && orders.totalDue !== null && orders.totalDue !== '0' ">
				<p>Total Balance Due: <span v-html="orders.totalDueDisplay"></span></p>
			</div>
			<div class="aw-allcomplete" v-if="orders.auctions == 'all complete' " >
				<h1>All Auction Orders are complete.</h1>
			</div>
			<h5>Payments Due</h5>
			<div class="aw-payments-due" v-if="orders.unpaid != undefined && orders.unpaid.length > 0">
	 			<table class="aw_dashboard">
		 			<thead>
		 				<tr  >
		 						<th class=" aw-order-type"><span class="nobr">Type</span></th>
		 						<th class=" aw-order-date"><span class="nobr">Date</span></th>
		 						<th class=" aw-order-number"><span class="nobr">PADDLE/INVOICE #</span></th>
		 						<th class=" aw-order-total"><span class="nobr">INVOICE AMT</span></th>
		 						<th class=" aw-interest"><span class="nobr">Interest</span></th>
		 						<th class=" aw-next-int"><span class="nobr">Next INT Date</span></th>
		 						<th class=" aw-order-totaldue"><span class="nobr">Total Due</span></th>
		 						<th class=" aw-order-pay"><span class="nobr">Pay</span></th>
		 				</tr>
		 			</thead>
		 			<tbody>
		 				<tr v-for="unpaid in orders.unpaid">
		 					<td class=" aw-order-type">{{ unpaid.order_type }}</td>
		 					<td class=" aw-order-date">{{ unpaid.order_date }}</td>
		 					<td class=" aw-order-number">{{ unpaid.order_number}}</td>
		 					<td class=" aw-order-total"><span v-html="unpaid.order_total"></span></td>
		 					<td class=" aw-interest"><span v-html="unpaid.interest"></span></td>
		 					<td class=" aw-next-int">{{ unpaid.due_date }}</td>
		 					<td class=" aw-total-due"><span v-html="unpaid.total_due"></span></td>
		 					<td class=" aw-pay-link"><span v-html="unpaid.pay_link"></span></td>
		 				</tr>
		 			</tbody>
	 		</table>
			</div>
			<div class="aw-payments-due" v-if="orders.unpaid == undefined || orders.unpaid.length == 0">
					<h6>You have no payments due.</h6>
			</div>
			<h5>Shipment Pending</h5>
			<div class="aw-pending-shipments" v-if="orders.shipped != undefined && orders.shipped.length > 0">
				<table class="aw_dashboard">
					<thead>
						<tr >
								<th class=" aw-order-type"><span class="nobr">Type</span></th>
								<th class=" aw-order-date"><span class="nobr">Date</span></th>
								<th class=" aw-order-number"><span class="nobr">PADDLE/INVOICE #</span></th>
								<th class=" aw-shipping-status"><span class="nobr">Shipping Status</span></th>
								<th class=" aw-shipping-details"><span class="nobr">Shipping Details</span></th>
								<th class=" aw-order-actions"><span class="nobr">Order Details</span></th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="shipped in orders.shipped">
							<td class=" aw-order-type">{{ shipped.order_type }}</td>
							<td class=" aw-order-date">{{ shipped.order_date }}</td>
							<td class=" aw-order-number">{{ shipped.order_number}}</td>
							<td class=" aw-shipping-status"><span v-html="shipped.ship_status"></td>
							<td class=" aw-shipping-details"><span v-html="shipped.tracking_link"></td>
							<td class=" aw-order-actions"><span v-html="shipped.details_button"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="aw-pending-shipments" v-if="orders.shipped == undefined || orders.shipped.length == 0">
				<h6> You have no pending shipments.</h6>
			</div>
			<!-- <div class="aw-pending-orders" v-if="orders.pending != undefined">
				<h5>Pending Orders</h5>
				<table class="aw_dashboard">
					<thead>
						<tr >
								<th class=" aw-order-type"><span class="nobr">Type</span></th>
								<th class=" aw-order-date"><span class="nobr">Date/LOC</span></th>
								<th class=" aw-order-number"><span class="nobr">PADDLE/INV #</span></th>
								<th class=" aw-order-actions"><span class="nobr">Order Details</span></th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="pending in orders.pending">
							<td class=" aw-order-type">{{ pending.order_type }}</td>
							<td class=" aw-order-date">{{ pending.order_date }}</td>
							<td class=" aw-order-number"><span v-html="pending.order_link"></span></td>
							<td class=" aw-order-actions"><span v-html="pending.details_button"></td>
						</tr>
					</tbody>
				</table>
			</div> -->
			<div class="aw-upcoming-payments" v-if="orders.payments != undefined && orders.payments.length > 0 " >
				<h5>Upcoming Payments Due to You</h5>
	 	    <table class="aw_dashboard">
	 					<thead>
	 	            <tr>
	 								<th class=" aw-order-type"><span class="nobr">Type</span></th>
	 								<th class=" aw-order-date"><span class="nobr">Date</span></th>
	 								<th class=" aw-order-actions"><span class="nobr">Auction No</span></th>
	 								<th class=" aw-pay-date"><span class="nobr">PMT Release Date</span></th>
	 								<th class=" aw-next-pay-date"><span class="nobr">Next PMT Date</span></th>
	 	            </tr>
	 	        </thead>
	 	        <tbody>
	 	            <tr v-for="payments in orders.payments">
	 	                <td class="aw-order-type">{{ payments.order_type }}</td>
	 	                <td class="aw-order-date">{{ payments.order_date }}</td>
	 	                <td class="aw-order-number">{{ payments.order_number }}</a></td>
	 									<td class="aw-payment-date">{{ payments.release_date }}</td>
										<td class="aw-next-payment">{{ payments.next_payment }}</td>
	 	            </tr>
	 	        </tbody>
	 	    </table>
			</div>
				<div class="aw-upcoming-payments" v-if="orders.payments == undefined || orders.payments.length == 0" >
				</div>
			<h5>Recent Orders</h5>
			<div class="aw-recent-order" v-if="orders.recent != undefined && orders.recent.length > 0" >
				<table class="aw_dashboard">
					<thead>
							 <tr class="" >
									 <th class=" aw-order-type"><span class="nobr">Order Type</span></th>
									 <th class=" aw-order-number"><span class="nobr">Order #</span></th>
									 <th class=" aw-order-date"><span class="nobr">Date</span></th>
									 <th class=" aw-order-number"><span class="nobr">Loc</span></th>
									 <th class=" aw-order-total"><span class="nobr">Total</span></th>
									 <th class=" aw-order-status"><span class="nobr">Status</span></th>
									 <th class=" aw-order-actions"><span class="nobr">Details</span></th>
							 </tr>
					 </thead>
						<tbody>
								<tr v-for="recent in orders.recent">
									<td class=" aw-order-type">{{ recent.order_type }}</td>
									<td class=" aw-order-number">{{ recent.order_number }}</a></td>
									<td class=" aw-order-date">{{ recent.order_date }}</td>
									<td class=" aw-order-date">{{ recent.order_loc }}</td>
									<td class=" aw-order-total"><span v-html="recent.total_due"></span></td>
									<td class=" aw-order-status"><span v-html="recent.order_status"></td>
									<td class=" aw-order-actions"><span v-html="recent.details_button"></td>
								</tr>

						</tbody>
				</table>
			</div>
			<div class="aw-recent-order" v-if="orders.recent == undefined || orders.recent.length == 0" >
				<h6>You have no recent orders. </h6>
			</div>
			<?php //echo AW_Dashboard::aw_appraisal_table(); ?>
			</div>
			 <?php
		 	//} // end for
	 } // end func

 }
}
