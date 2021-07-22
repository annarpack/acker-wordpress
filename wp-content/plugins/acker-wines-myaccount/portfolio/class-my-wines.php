<?php

namespace AckerWines;

if (!class_exists('AW_MY_Wines')) {
    class AW_MY_Wines
    {

        const AW_MY_WINES_ENDPOINT = 'my-wines';

        public function init()
        {
            //wp_enqueue_style( 'account-style');
        }

        public function aw_my_wines_get_all_auction_orders($data)
        {
            $auction_data = json_decode($data);
            $auction_orders = $auction_data->data;
            $all_orders = array();
            $order_data = array();
            foreach ($auction_orders as $order) {
                $location = $order->siteCode;
                if ($location == 'NY' || $location == 'US' || $location == 'Web') {
                    $order_total = $order->totalAmount;
                    if ($order_total == null) { $order_total = $order->totalAmount; }
                    if ($order_total == '.0000') { $order_total = $order->totalAmount; }
                    $currancy = '$';
                } else {
                    $order_total = $order->totalAmountLocal;
                    if ($order_total == null) { $order_total = $order->totalAmountLocal; }
                    if ($order_total == '.0000') { $order_total = $order->totalAmountLocal; }
                    $currancy = $order->currencySymbolLocal;
                }
                $order_total_format = number_format($order_total, 2, '.', 3);
                $order_total_display = '<span class="woocommerce-Price-amount amount">' . $currancy . ' ' . $order_total_format . ' </span>';
                $order_type = 'Auction';
                $auction_date = $order->auctionDate->date;
                $auction_timezone = $order->auctionDate->timezone;
                $order_date_data = new \DateTime($auction_date);
                $order_date = date_format($order_date_data, 'Y-m-d');
								$auction_number = $order->auctionNumber;
                $paddle_id = $order->paddleId;
                $sale_date = $order->saleDate;
								if(isset($auction_number)){
									$order_number = $auction_number . '-' . $paddle_id;
								}
                //$order_number_display = '<span class="woocommerce-Price-amount amount"> ' . $order_number . ' </span>';
                $action_button = '<button class="aw-appraisal" ><input class="aw-checkbox" type=checkbox data-id="' . $product_id . '" data-row="' . $i . '"  /></button>';
                if (isset($paddle_id)) {
                    $auction_api = new API\AuctionProgApi();
                    $orderDetails = $auction_api->getInvoiceDetails($paddle_id, $auction_api->getApcId());
                    $orderDetails = json_decode($orderDetails);
                    $orderDetails = $orderDetails->data;

                    if (isset($orderDetails)) {
                        foreach ($orderDetails as $wine_details) {
                            $wine_name = $wine_details->wineName;
                            $quantity = $wine_details->quantity;
                            $producer = $wine_details->producer;
                            $designation = $wine_details->designation;
                            // $vintage = $wine_details->vintage;
                            $format = $wine_details->format;
                            $region = $wine_details->region_description;
                            $vintage = $wine_details->vintage;
                            //echo var_dump($vintage);
                            // $vintage = $wine_details['vintage'];
                            // echo var_dump($vintage);
                            $item_data = array(
                                'order_type' => $order_type,
                                'order_date' => $sale_date,
                                'order_number' => $order_number,
                                'order_link' => $order_number,
                                'wine_qty_display' => $quantity,
                                'wine_qty' => $quantity,
                                'wine_format' => $format ?? '',
                                'wine_vintage' => $vintage ?? '',
                                'wine_link' => $wine_name,
                                'wine_name' => $wine_name,
                                'wine_region' => $region ?? '',
                                'wine_designation' => $designation ?? '',
                                'wine_producer' => $producer ?? '',
                                'action_button' => $action_button
                            );
														array_push($all_orders, $item_data);
                        }
                    } //if orderDetails isset
                } // if isset order number
            } // end for each
            return $all_orders;
            //return $orderDetails;
        }

        public static function aw_my_wines_get_all_retail_orders()
        {
            $all_wines = array();
            $wine_data = array();
            $current_user = wp_get_current_user();
            $user = $atts['user'] ? $atts['user'] : get_current_user_id();
            $customer_orders = get_posts(array(
                'numberposts' => -1,
                'meta_key' => '_customer_user',
                'meta_value' => $user,
                'post_type' => wc_get_order_types(),
                'post_status' => array_keys(wc_get_order_statuses()),
            ));
            $i = 0;
            if ($customer_orders && (count($customer_orders) > 0)) {
                foreach ($customer_orders as $customer_order) {
                    $order = wc_get_order($customer_order);
                    $order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
                    $order_url = $order->get_view_order_url();
                    $order_number = $order->get_order_number();
                    //$order_date = wc_format_datetime($order->get_date_created());
                    $date = wc_format_datetime($order->get_date_created());
                    $order_date_data = new \DateTime($date);
                    $order_date = date_format($order_date_data, 'Y-m-d');
                    $order_total = $order->get_formatted_order_total();
                    $item_count = $order->get_item_count();
                    $order_id = $order->get_order_number();
                    $order_url = $order->get_view_order_url();
                    $order_number = $order->get_order_number();
                    $order_total = $order->get_formatted_order_total();
                    $order_status = $order->get_status();
                    $order_type = $order->get_type();
										$item_data = null;
                    foreach ($order_items as $item_id => $item) {
                        if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
                            continue;
                        }
                        if (metadata_exists('post', $order_id, '_eventid')) {
                            //break;
                            $event_id = get_post_meta($item['product_id'], '_eventid', true);
                            if (isset($event_id) && $event_id != '') {
                                $order_type = 'tickets';
                            }
                        }
                        if($order_type == 'shop_order') {
                            $product = $item->get_product();
                            if($product) { // start if this is a product
                                $product_id = $product->get_id();
																if(isset($product_id)){
	                                $is_visible = $product && $product->is_visible();
	                                $product_permalink = apply_filters('woocommerce_order_item_permalink', $is_visible ? $product->get_permalink($item) : '', $item, $order);
	                                $product_name = apply_filters('woocommerce_order_item_name', $product_permalink ? sprintf('<a href="%s">%s</a>', $product_permalink, $item->get_name()) : $item->get_name(), $item, $is_visible);
	                                $product_qty = apply_filters('woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf('&times; %s', $item->get_quantity()) . '</strong>', $item);
	                                $product_total = wc_price($item->get_total());
	                                $current_price = ($product->get_id() && $product->is_purchasable()) ? $product->get_price_html() : '';
																	$retail = has_term('retail', 'product_cat', $product_id);
																	$fine_rare = has_term('fine-rare', 'product_cat', $product_id);
																	$attr_format = null; $attr_vintage = null; $attr_region = null; $attr_producer = null;
																	if($retail || $fine_rare){
                                    $attribute_vintage = get_the_terms($product->get_id(), 'pa_vintage');
                                    if(isset($attribute_vintage)) {
																			$attr_vintage = $attribute_vintage[0]->name;
																			$attribute_region = get_the_terms($product->get_id(), 'pa_region');
																			$attr_region = $attribute_region[0]->name;
																			$attribute_designation = get_the_terms($product->get_id(), 'pa_vineyard');
																			$attr_designation = $attribute_designation[0]->name;
																			$attribute_producer = get_the_terms($product->get_id(), 'pa_producer');
																			$attr_producer = $attribute_producer[0]->name;
																			$attribute_format = get_the_terms($product->get_id(), 'pa_bottle-size');
																			if(isset($attribute_format)) {
																				$attr_format = $attribute_format[0]->name;
																			}
																			if($attr_region !== 'Accessory'){
																				$order_type_title = AW_Purchase_History::aw_purchase_history_get_order_type($order_type);
		                                    $order_link = '<a href="' . $order_url . '" >' . $order_number . '</a>';
		                                    $wine_link = '<a href="' . $product_permalink . '" >' . $product_name . '</a>';
		                                    $action_button = '<button class="aw-appraisal" ><input class="aw-button" type=checkbox data-id="' . $product_id . '" data-row="' . $i . '"  /></button>';
		                                    $item_data = array(
		                                        'order_type' => $order_type_title,
		                                        'order_date' => $order_date,
		                                        'order_number' => $order_number,
		                                        'order_link' => $order_link,
																						'wine_name' => $item->get_name(),
																						'wine_link' => $wine_link,
		                                        //'wine_qty_display' => $product_qty,
		                                        'wine_qty' => $item->get_quantity(),
		                                        'wine_format' => $attr_format ?? '',
		                                        'wine_vintage' => $attr_vintage ?? '',
		                                        'wine_region' => $attr_region ?? '',
		                                        'wine_designation' => $attr_designation ?? '',
		                                        'wine_producer' => $attr_producer ?? '',
		                                        'action_button' => $action_button
		                                    );
		                                    $i++;
																				array_push($wine_data, $item_data);
																			}
                                    }
																	}// if retail or fine and rare
                                }// end if product id exists
                            }//if order = shop order

                        } // end if this is a product
                    } //for each item
                }//end for each order
                return $wine_data;
            }//end if customer orders
            else {
                return "You have no retail orders in your account.";
            } // end else
        } // end function


        public static function aw_my_wines_get_combined_data()
        {
            $retail_results = array();
            $auction_results = array();

            $retail_temp = AW_MY_Wines::aw_my_wines_get_all_retail_orders();

            if (isset($retail_temp) && is_array($retail_temp)) {
                $retail_results = $retail_temp;
            }

            $auction_api = new API\AuctionProgApi();
            $auction_data = $auction_api->getInvoices($auction_api->getApcId());
            if (isset($auction_data)) {
                $auction_results = AW_MY_Wines::aw_my_wines_get_all_auction_orders($auction_data);
            }
						//return $auction_results;
            return array_merge($retail_results, $auction_results);

						//return $retail_results;

        } // end function combined data


    } // end class
} // end if class
$mywines = new AW_MY_Wines;
$mywines->init();
