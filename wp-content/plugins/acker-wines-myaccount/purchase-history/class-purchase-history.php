<?php

namespace AckerWines;

if ( ! class_exists( 'AW_Purchase_History' ) ) {
    class AW_Purchase_History {

        const AW_PURCHASE_HISTORY_ENDPOINT = 'purchase-history';

        public function init(){
            if ( ! defined( 'AW_ACCOUNT_DIR' ) ) {
                define( 'AW_ACCOUNT_DIR', plugin_dir_path( __FILE__ ) );
            }
            $includes_path = plugin_dir_path( __FILE__ ) . '../includes/';
            require_once $includes_path . 'ApiHelper.php';
            require_once $includes_path . 'ApiRequestResult.php';
            //add_action( 'wp_ajax_aw_purchase_history_filter_click', array( $this, 'aw_purchase_history_filter_click' ));
            //add_action( 'wp_ajax_nopriv_aw_purchase_history_filter_click', array( $this, 'aw_purchase_history_filter_click' ));
            ApiHelper::registerAjaxEndpoint('aw_purchase_history_filter_click', true);
            add_shortcode('aw_purchase_history_filter', array( $this, 'aw_purchase_history_filter_shortcode'));
            aw_shared_plugin_init();
        }

        // function aw_purchase_history_filter_shortcode( $atts ){
        //     $filter = $atts['filter'];
        //     $page = $atts['page'];
        //     echo '<p>' . var_dump($filter) . '</p>';
        //     echo '<p>' . var_dump($page) . '</p>';
        //
        //     $a = shortcode_atts( array(
        // 		'filter' => $filter,
        //         'page' => $page
        // 	), $atts );
        //
        // }

        public function aw_purchase_history_get_template( $template_name , $filter ){
            $path = plugin_dir_path( __FILE__ ) . 'templates/my-' . $template_name . '.php';
            include($path);
            //return \aw_purchase_history_return_products($filter);
            //return AW_Purchase_History::aw_purchase_history_get_main_content($template_name, $filter);
        }
        public function aw_purchase_history_account_menu_items($items) {
            $items[AW_PURCHASE_HISTORY_ENDPOINT] = __('Purchase History', 'acker_wines');
            return $items;
        }

        public function aw_purchase_history_get_filters(){
            $slugs = ['invoices', 'shop_order', 'auction', 'tickets', 'shop_subscription'];
            $titles = ['Invoices', 'Wines', 'Auction', 'Tickets', 'Wine Club'];
            $filters = array(
                'slugs' => $slugs,
                'titles' => $titles
            );
            return $filters;
        }

        public static function aw_purchase_history_filter_buttons(){
            $filters = AW_Purchase_History::aw_purchase_history_get_filters();
            $filter_slugs = $filters['slugs'];
            $filter_titles = $filters['titles'];
            $length = sizeof($filter_titles);
            echo '<div id="purchase-history-filter-buttons-section" class="tabs" data-tab role="tablist">';
            for($i = 0; $i < sizeof($filter_slugs); $i++){
                echo '<div class="tab filter-button" data-id=' . $filter_slugs[$i] . ' type="button" >';
                    echo '<input type="radio" id="tab-' . $i . '" data-id="' . $filter_slugs[$i] . '" class="filter-buttons" >';
                    echo '<label for="tab-' . $i . '" data-id="' . $filter_slugs[$i] . '" >';
                        echo $filter_titles[$i];
                    echo '</label>';
                echo '</div>';
            }
            echo '</div>';
            echo '<div class="loading"></div>';
        }
        public function aw_purchase_history_get_order_type($order_type){
            $order_type_array = ['shop_order', 'shop_subscription', 'tickets'];
            $order_titles_array = ['Retail Order', 'Subscription', 'Event Tickets'];
            $index = array_search($order_type, $order_type_array);
            $type_title = $order_titles_array[$index];
            return $type_title;
        }
        public function aw_purchase_history_get_filter_title($filter){
            $filters = AW_Purchase_History::aw_purchase_history_get_filters();
            $filter_slugs = $filters['slugs'];
            $filter_titles = $filters['titles'];
            $length = sizeof($filter_titles);
            $index = array_search($filter, $filter_slugs);
            $type_title = $filter_titles[$index];
            return $type_title;
        }
        public static function aw_purchase_history_get_invoice_data($order, $item){
            $order_url = $order->get_view_order_url();
            $order_number = $order->get_order_number();
            $order_date = wc_format_datetime($order->get_date_created());
            $order_total = $order->get_formatted_order_total();
            $item_count = $order->get_item_count();
            $order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
            $order_id = $order->get_order_number();
            $order_url = $order->get_view_order_url();
            $order_number = $order->get_order_number();
            $order_date = wc_format_datetime($order->get_date_created());
            $order_total = $order->get_formatted_order_total();
            $order_status = $order->get_status();
            $details_button_url = !($order_id) ? '' : sprintf(
            '<a href="%s" rel="nofollow" data-order_id="%s" class="button %s ">%s</a>',
            esc_url($order_url),
            esc_attr($order_id),
            'order_details_button',
            'Details'
            );

            $st = \WC_Shipment_Tracking_Actions::get_instance();
            if(isset($st)){
                $tracking_items = $st->get_tracking_items( $order_id );
                if(($order_status == 'completed') && isset($tracking_items)) {
                    $tracking_item = $tracking_items[0];
                    if( isset($tracking_item['date_shipped'])){
                        $order_status = '<time datetime=" ' . date( 'Y-m-d', $tracking_item['date_shipped'] ) . ' " title="' .  date( 'Y-m-d', $tracking_item['date_shipped'] ) . ' "> Shipped ' .  date_i18n( get_option( 'date_format' ), $tracking_item['date_shipped'] ) . '</time></br>';
                        } //end if
                }//end if
            }
            $tickets = get_post_meta( $order_id , '_eventid', true);
            //echo var_dump($tickets);
            if($tickets != ''){
             $order_type = 'Event Ticket';
            } else {
             $order_type = $order->get_type();
            }
            $order_type_title = AW_Purchase_History::aw_purchase_history_get_order_type($order_type);
            $invoice_data = array(
                'order-date' => $order_date,
                'order-number' => $order_number,
                'order-url' => $order_url,
                'order-type' => $order_type,
                'order-total' => $order_total,
                'order-status' => $order_status,
                'order-button' => $details_button_url
            );
            return $invoice_data;

        } // end func my orders content


        public static function aw_purchase_history_get_products_data($order, $item){
            $order_url = $order->get_view_order_url();
            $order_number = $order->get_order_number();
            $order_date = wc_format_datetime($order->get_date_created());
            $order_total = $order->get_formatted_order_total();
            $item_count = $order->get_item_count();
            $order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));

            //echo '<p>filter slug ' . $filter . '</p>';
            $product = $item->get_product();
            $is_visible = $product && $product->is_visible();
            $product_permalink = apply_filters('woocommerce_order_item_permalink', $is_visible ? $product->get_permalink($item) : '', $item, $order);
            $product_name = apply_filters('woocommerce_order_item_name', $product_permalink ? sprintf('<a href="%s">%s</a>', $product_permalink, $item->get_name()) : $item->get_name(), $item, $is_visible);
            $product_qty = apply_filters('woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf('&times; %s', $item->get_quantity()) . '</strong>', $item);
            $product_total = wc_price($item->get_total());
            $current_price = ($product->id && $product->is_purchasable()) ? $product->get_price_html() : '';
            $add_button_url = !($product->id && $product->is_purchasable()) ? '' : sprintf(
                '<a href="%s" rel="nofollow" data-product_id="%s" class="button %s product_type_%s">%s</a>',
                esc_url($product->add_to_cart_url()),
                esc_attr($product->id),
                'add_to_cart_button',
                esc_attr($product->product_type),
                esc_html('Buy Again')
            );
            $order_type = $order->get_type();
            $event_id = get_post_meta( $item['product_id'] , '_eventid', true);
            if(isset($event_id) && $event_id != ''){
                $order_type = 'tickets';
            }
            $product_type = AW_Purchase_History::aw_purchase_history_get_order_type($order_type);
            $product_name;
            if($order_type == 'tickets'){
                $event = get_post($event_id);
                $event_permalink = get_permalink($event);
                $product_name = '<a href=' . esc_url($event_permalink) . '> ' . $product_name . '</a>' . $product_qty;
            } else {
                $product_name = '' . $product_name . '' . $product_qty;
            }
            $product_arr = array(
                'order-number' => $order_number,
                'order-url' => $order_url,
                'order-type' => $order_type,
                'product-type' => $product_type,
                'product-name' => $product_name,
                'product-total' => $product_total,
                'product-price' => $current_price,
                'product-button' => $add_button_url
            );

            return $product_arr;
        }

        public static function aw_purchase_history_show_content($page, $filter) {
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
                if($page == 'invoices'){
                    echo '<h1>Invoices</h1>';
                    echo '<table id="aw-my-orders-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;" >';
                    echo '<thead><tr>';
                    echo '<th class="woocommerce-orders-table__header aw-order-date"><span class="nobr">Order Date</span></th>';
                    echo '<th class="woocommerce-orders-table__header aw-order-number"><span class="nobr">Order #</span></th>';
                    echo '<th class="woocommerce-orders-table__header aw-order-type"><span class="nobr">Order Type</span></th>';
                    echo '<th class="woocommerce-orders-table__header aw-order-total"><span class="nobr">Order Total</span></th>';
                    echo '<th class="woocommerce-orders-table__header aw-order-status"><span class="nobr">Order Status</span></th>';
                    echo '<th class="woocommerce-orders-table__header aw-order-actions"><span class="nobr">Order Details</span></th>';
                }
                if($page == 'products'){
                    $title = AW_Purchase_History::aw_purchase_history_get_filter_title($filter);
                    echo '<h1>' . $title . '</h1>';
                    echo '<table id="aw-my-products-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;" >';
                    echo '<thead><tr>';
                    echo '<th class="woocommerce-orders-table__header aw-order-number"><span class="nobr">Order #</span></th>';
                    echo '<th class="woocommerce-orders-table__header  aw-product-type"><span class="nobr">Order Type</span></th>';
                    echo '<th class="woocommerce-orders-table__header  aw-product-name"><span class="nobr">Product Name</span></th>';
                    echo '<th class="woocommerce-orders-table__header  aw-product-total"><span class="nobr">Paid Price</span></th>';
                    echo '<th class="woocommerce-orders-table__header  aw-product-price"><span class="nobr">Current Price</span></th>';
                    echo '<th class="woocommerce-orders-table__header  aw-product-actions"><span class="nobr">Actions</span></th>';
                }
                echo '</tr></thead><tbody>';

                foreach ($customer_orders as $customer_order) {
                    $order = wc_get_order($customer_order);
                    $order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));

                    foreach ($order_items as $item_id => $item) {
                        if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
                            continue;
                        }
                        if($page == 'invoices'){
                            $data = AW_Purchase_History::aw_purchase_history_get_invoice_data($order, $item);
                            $type = $data['order-type'];
                            $order_type_title =  AW_Purchase_History::aw_purchase_history_get_order_type($type);
                            echo '<tr class="woocommerce-orders-table__row">';
                                echo '<td class="woocommerce-orders-table__cell aw-order-date">' . $data['order-date'] . '</td>';
                                echo '<td class="woocommerce-orders-table__cell aw-order-number">';
                                    echo '<a href="' . $data['order-url'] . '">' . $data['order-number'] . '</a>';
                                echo '</td>';
                                echo '<td class="woocommerce-orders-table__cell aw-order-type">' . $order_type_title . '</td>';
                                echo '<td class="woocommerce-orders-table__cell aw-order-total">' . $data['order-total'] . '</td>';
                                echo '<td class="woocommerce-orders-table__cell aw-order-status">' . $data['order-status'] . '</td>';
                                echo '<td class="woocommerce-orders-table__cell aw-order-actions">' . $data['order-button'] . '</td>';
                            echo '</tr>';
                        }
                        if($page == 'products'){
                            $data = AW_Purchase_History::aw_purchase_history_get_products_data($order, $item);
                            $type = $data['order-type'];
                            $order_type_title =  AW_Purchase_History::aw_purchase_history_get_order_type($type);
                            if($type == $filter){
                                echo '<tr class="woocommerce-orders-table__row aw-product-order">';
                                    echo '<td class="woocommerce-orders-table__cell aw-order-number"><a href="' . $data['order-url'] . '">' . $data['order-number'] . '</a></td>';
                                    echo '<td class="woocommerce-orders-table__cell aw-product-type">' . $data['product-type'] . '</td>';
                                    echo '<td class="woocommerce-orders-table__cell aw-product-name">' . $data['product-name'] . '</td>';
                                    echo '<td class="woocommerce-orders-table__cell aw-product-total">' . $data['product-total'] . '</td>';
                                    echo '<td class="woocommerce-orders-table__cell aw-product-price">' . $data['product-price'] . '</td>';
                                    echo '<td class="woocommerce-orders-table__cell aw-product-actions">' . $data['product-button'] . '</td>';
                                echo '</tr>';
                                }
                            }
                        } // end for each item
                    } // end for each order
                echo '</tbody></table>';

                ?>
                <script type="text/javascript">
                    <!--
                    var page = '<?php echo $page; ?>';
                    if(page === 'products'){ var title = '#aw-my-products-table' }
                    if(page === 'invoices'){ var title = '#aw-my-orders-table' }
                    var table = jQuery(title).DataTable({
                        "responsive": true,
                        "scrollY": '770px',
                        "order": [[1, "desc"]],
                        "paging": false,
                        "bLengthChange": true,
                        "bFilter": true
                    });
                    -->
                </script>
            <?php
            } // end if

        } // end aw_purchase_history_get_main_content


    } // end class defninition
} // end if
$purchase_history_endpoint = new AW_Purchase_History();
$purchase_history_endpoint->init();
