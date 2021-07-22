<?php
const AW_MY_AUCTIONS_ENDPOINT = 'my-auctions';

function aw_my_auctions_my_account_init() {
    aw_shared_plugin_init();
    add_rewrite_endpoint(AW_MY_AUCTIONS_ENDPOINT, EP_ROOT | EP_PAGES);
    wp_enqueue_script('aw-chosen-jquery-js');
    wp_enqueue_script('aw-jquery-ui-js');
    wp_enqueue_script('aw-jquery-dataTables-js');
    wp_enqueue_style('aw-jquery-ui-css-1.12.1');
    wp_enqueue_style('aw-jquery-dataTables-css');
    wp_enqueue_style('aw-plugins-css');
}
add_action('init', 'aw_my_auctions_init');

function aw_my_auctions_my_account_menu_items($items) {
    //there is already an end point for orders called "orders" with the url /my-account/orders
    $items[AW_MY_AUCTIONS_ENDPOINT] = __('My Auctions', 'acker_wines');
    return $items;
}
add_filter('woocommerce_account_menu_items', 'aw_my_auctions_my_account_menu_items', 10, 1);

function aw_my_auctions_get_order_type($order_type){
    $order_type_array = ['shop_order', 'shop_subscription', 'tickets'];
    $order_titles_array = ['Retail Order', 'Subscription', 'Event Tickets'];
    $index = array_search($order_type, $order_type_array);
    $type_title = $order_titles_array[$index];
    return $type_title;
}

function aw_my_auctions_my_account_get(){
    $data = $_POST['data'];
    echo '<p>' . var_dump($data) . '</p>';
}
function aw_my_auctions_my_account_content() {
    //$user = $atts['user'] ? $atts['user'] : get_current_user_id();
    // $user = get_current_user_id();
    // $customer_orders = get_posts(array(
    //     'numberposts' => -1,
    //     'meta_key'=> '_customer_user',
    //     'meta_value'=> $user,
    //     'post_type' => wc_get_order_types(),
    //     'post_status' => array_keys(wc_get_order_statuses()),
    // ));


    // if ($customer_orders) {
    ?>
    <h1>Auction Invoices</h1>
        <table id="aw-my-orders-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;">
        <thead>
            <tr class="woocommerce-orders-table__header" >
                <th class="woocommerce-orders-table__header aw-order-date"><span class="nobr">Order Date</span></th>
                <th class="woocommerce-orders-table__header aw-order-number"><span class="nobr">Order #</span></th>
                <th class="woocommerce-orders-table__header aw-order-type"><span class="nobr">Order Type</span></th>
                <th class="woocommerce-orders-table__header aw-order-total"><span class="nobr">Order Total</span></th>
                <th class="woocommerce-orders-table__header aw-order-status"><span class="nobr">Order Status</span></th>
                <th class="woocommerce-orders-table__header aw-shipping-status"><span class="nobr">Shipping Status</span></th>
                <th class="woocommerce-orders-table__header aw-order-actions"><span class="nobr">Order Details</span></th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($customer_orders as $customer_order) {
             $order = wc_get_order($customer_order);
             $order_id = $order->ID;
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

            $st = WC_Shipment_Tracking_Actions::get_instance();
    		$tracking_items = $st->get_tracking_items( $order_id );

            $tickets = get_post_meta( $order_id , '_eventid', true);
            //echo var_dump($tickets);
            if($tickets != ''){
                $order_type = 'Event Ticket';
            } else {
                $order_type = $order->get_type();
            }

                    ?>
        <tr class="woocommerce-orders-table__row">
            <td class="woocommerce-orders-table__cell aw-order-date"><?php echo $order_date; ?></td>
            <td class="woocommerce-orders-table__cell aw-order-number">
                <a href="<?php echo $order_url; ?>"><?php echo $order_number; ?></a>
            </td>
            <td class="woocommerce-orders-table__cell aw-order-type">
                <?php $type = aw_my_auctions_get_order_type($order_type);
                echo $tickets;
                echo $type; ?></td>
            <td class="woocommerce-orders-table__cell aw-order-total"><?php echo $order_total ?></td>

            <td class="woocommerce-orders-table__cell aw-order-status">
                <?php if($order_status == 'completed') {
                    $tracking_item = $tracking_items[0];
                        //echo '<p>' . $tracking_item['status_shipped'] . '</p><br/>'; ?>
                        <?php if( isset($tracking_item['date_shipped'])){ ?>
                        <time datetime="<?php echo date( 'Y-m-d', $tracking_item['date_shipped'] ); ?>" title="<?php echo date( 'Y-m-d', $tracking_item['date_shipped'] ); ?>">
                            Shipped <?php echo date_i18n( get_option( 'date_format' ), $tracking_item['date_shipped'] ); ?>
                        </time></br>
                        <?php } //end if
                } elseif($order_status !== 'completed') {
                    echo $order_status;
                 } //end else ?>

            </td>
            <td class="woocommerce-orders-table__cell aw-shipping-status " >
                <?php
                $formatted = $st->get_formatted_tracking_item( $order_id, $tracking_item );
                if(!defined('WC_SHIPMENT_TRACKING_VERSION')){
                    echo 'Tracking Unavailable';
                }
                if( isset($tracking_item['tracking_number']) ) {
                    if ( '' !== $formatted['formatted_tracking_link'] ) {
                        $url = $formatted['formatted_tracking_link'];
                        ?>
                        <a href="<?php echo esc_url( $url ); ?>" target="_blank" class="button" >
                            TRACK
                        </a>
                <?php  } // end if
                }//end if
                else { ?>
                    Tracking Unavilable
                <?php }?>


            </td>
            <td class="woocommerce-orders-table__cell aw-order-actions">
                <?php echo $details_button_url; ?>
            </td>
        </tr>
        <?php
        }// for each customer order
        ?>
        </tbody></table>

        <script type="text/javascript">
        <!--
        var table = jQuery("#aw-my-orders-table").DataTable({
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
    //} // if $customer_orders
} // end func my orders content
add_action('woocommerce_account_' . AW_MY_AUCTIONS_ENDPOINT . '_endpoint', 'aw_my_auctions_content');
