<?php
const AW_MY_DASHBOARD_ENDPOINT = 'my-dashboard';

function aw_my_dashboard_init() {
    aw_shared_plugin_init();
    add_rewrite_endpoint(AW_MY_DASHBOARD_ENDPOINT, EP_ROOT | EP_PAGES);
    wp_enqueue_script('aw-jquery-ui-js');
    wp_enqueue_script('aw-jquery-dataTables-js');
    wp_enqueue_style('aw-jquery-ui-css-1.12.1');
    wp_enqueue_style('aw-jquery-dataTables-css');
}
add_action('init', 'aw_my_dashboard_init');

function aw_my_dashboard_account_menu_items($items) {
    $items[AW_MY_DASHBOARD_ENDPOINT] = __('My Dashboard', 'acker_wines');
    return $items;
}
add_filter('woocommerce_account_menu_items', 'aw_my_dashboard_account_menu_items', 10, 1);

function aw_my_dashboard_get_alerts($user, $orders_array, $last){
    //var_dump($user);
    //var_dump(wc_get_customer_total_spent($user) );
    //var_dump(wc_get_is_pending_statuses($user) );
    //woocommerce_upsell_display()
    //wc_get_account_orders_actions
    //wc_get_customer_order_count
    //wc_orders_count()
    //wc_processing_order_count

    if( wc_has_notice($user) == true){
        //echo '<h3>' . $notice . "</h3>";
    }
    $processing = $last['processing'];
    $pending = $last['pending'];
    if($processing > 0){
        echo '<p class="alert-message">You have ' . $processing . ' processing orders </p>';
    }

    if($pending > 0){
        echo '<p class="alert-message">You have ' . $pending . ' pending orders </p>';
    }

}
function aw_my_dashboard_get_orders_sections($user, $orders_array, $section){

    ?>
    <table id="aw-my-products-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;">
    <thead></thead>
    <?php
    foreach($orders_array as $order){
        if( isset($order) && $order['order_date'] != ''){
                //wcs_order_contains_subscription
                //wcs_get_subscriptions_for_order
            if($order['order_status'] == $section || ( $order['order_type'] == $section && $order['order_status'] != 'completed' ) ){
            ?>
                <tr>
                    <td class="woocommerce-orders-table__cell aw-order-date"><?php echo $order['order_date']; ?></td>
                    <td class="woocommerce-orders-table__cell aw-order-total"><?php echo $order['order_total'] ?></td>
                    <td class="woocommerce-orders-table__cell aw-order-actions">
                        <?php echo $order['details_button_url']; ?>
                    </td>
                </tr>
            <?php
            }//end if
        }//end if
    } //end foreach ?>
    </table> <?php

}


function aw_my_dashboard_get_recent_orders($user, $orders_array){
    ?>
    <table id="aw-my-products-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;">
    <thead>
        <tr class="woocommerce-orders-table__header" >
            <th class="woocommerce-orders-table__header aw-order-number"><span class="nobr">Order #</span></th>
            <th class="woocommerce-orders-table__header aw-order-date"><span class="nobr">Order Date</span></th>
            <th class="woocommerce-orders-table__header aw-order-total"><span class="nobr">Order Total</span></th>
            <th class="woocommerce-orders-table__header aw-order-status"><span class="nobr">Order Status</span></th>
            <th class="woocommerce-orders-table__header aw-shipping-status"><span class="nobr">Shipping Details</span></th>
            <th class="woocommerce-orders-table__header aw-order-actions"><span class="nobr">Order Details</span></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach($orders_array as $order){
        if( $order['order_date'] != ''){
        ?>
           <tr class="woocommerce-orders-table__row">
               <td class="woocommerce-orders-table__cell aw-order-number">
                   <a href="<?php echo $order['order_url']; ?>"><?php echo $order['order_number']; ?></a>
               </td>
               <td class="woocommerce-orders-table__cell aw-order-date"><?php echo $order['order_date']; ?></td>
               <td class="woocommerce-orders-table__cell aw-order-total"><?php echo $order['order_total'] ?></td>

               <td class="woocommerce-orders-table__cell aw-order-status">
                   <?php if($order['order_status'] == 'completed') {
                       $tracking_items = $order['tracking_items'];
                       $tracking_item = $tracking_items[0];
                           //echo '<p>' . $tracking_item['status_shipped'] . '</p><br/>'; ?>
                           <?php if( isset($tracking_item['date_shipped'])){ ?>
                           <time datetime="<?php echo date( 'Y-m-d', $tracking_item['date_shipped'] ); ?>" title="<?php echo date( 'Y-m-d', $tracking_item['date_shipped'] ); ?>">
                               Shipped <?php echo date_i18n( get_option( 'date_format' ), $tracking_item['date_shipped'] ); ?>
                           </time></br>
                           <?php } //end if
                   } elseif($order['order_status'] !== 'completed') {
                       echo $order['order_status'];
                    } //end else ?>

               </td>
               <td class="woocommerce-orders-table__cell aw-shipping-status " style="<?php echo $td_column_style; ?>">
                   <?php
                   $st = WC_Shipment_Tracking_Actions::get_instance();
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
                   <?php echo $order['details_button_url']; ?>
               </td>
           </tr>
           <?php
            }//end if
       }//end for each
    ?> </tbody></table> <?php
}

function aw_my_dashboard_get_orders_info($user){
    $customer_orders = get_posts(array(
        'numberposts' => -1,
        'meta_key'=> '_customer_user',
        'meta_value'=> $user,
        'post_type' => wc_get_order_types(),
        'post_status' => array_keys(wc_get_order_statuses()),
    ));
    $pending = 0; $processing = 0;
    if ($customer_orders) {
        $orders_array = array();
        foreach ($customer_orders as $customer_order) {
            $order = wc_get_order($customer_order);
            $order_id = $order->ID;
            $order_url = $order->get_view_order_url();
            $order_number = $order->get_order_number();
            $order_date = wc_format_datetime($order->get_date_created());
            $order_total = $order->get_formatted_order_total();
            $order_status = $order->get_status();
            $order_type = $order->get_type();

            $st = WC_Shipment_Tracking_Actions::get_instance();
    		$tracking_items = $st->get_tracking_items( $order_id );

            //echo '<p>' . $type . '</p>';

            $details_button_url = !($order_id) ? '' : sprintf(
               '<a href="%s" rel="nofollow" data-order_id="%s" class="button %s ">%s</a>',
               esc_url($order_url),
               esc_attr($order_id),
               'order_details_button',
               'Details'
           );

           if($order_status == 'processing'){ $processing++; }
           if($order_status == 'pending'){$pending++;}
          array_push( $orders_array, array(
               'order' => $order,
               'order_id' => $order_id,
               'order_url' => $order_url,
               'order_number' => $order_number,
               'order_date' => $order_date,
               'order_total' => $order_total,
               'order_status' => $order_status,
               'order_type' => $order_type,
               'tracking_items' => $tracking_items,
               'details_button_url' => $details_button_url
           ));
       }
    }
    $p = array('processing' => $processing, 'pending' => $pending);
    array_push( $orders_array, $p);

    return $orders_array;
}




function aw_my_dashboard_get_section_content($user){
    $sections = ['alerts', 'pending-orders', 'processing-orders', 'subscriptions', 'recent-orders'];
    $orders_array = aw_my_dashboard_get_orders_info($user);
    $max = (sizeof($orders_array) - 1);
    $last = $orders_array[$max];
    $processing = $last['processing'];
    $pending = $last['pending'];
    for($i=0;$i< sizeof($sections); $i++){
    ?>
    <section id=<?php echo "dashboard-" . $sections[$i] . ""?> >
        <?php if($sections[$i] == 'alerts'){ ?>
            <h5>Messages</h5> <?php
            aw_my_dashboard_get_alerts($user, $orders_array, $last);
        }?>
        <?php if($sections[$i] == 'recent-orders'){ ?>
            <h5>Recent Orders</h5> <?php
            aw_my_dashboard_get_recent_orders($user, $orders_array);
        }?>
        <?php if($sections[$i] == 'processing-orders'){?>
            <h5>Processing Orders</h5>
            <p><?php echo $processing . ' processing orders';
            if($processing > 0){
                aw_my_dashboard_get_orders_sections($user, $orders_array, 'processing');
            }
        }?>
        <?php if($sections[$i] == 'pending-orders'){?>
            <h5>Pending Orders</h5>
            <p><?php echo $pending . ' pending orders';
            if($pending > 0){
                aw_my_dashboard_get_orders_sections($user, $orders_array, 'pending');
            }
        }?>
        <?php if($sections[$i] == 'subscriptions'){ ?>
            <h5>Subscription Orders</h5> <?php
            aw_my_dashboard_get_orders_sections($user, $orders_array, 'shop_subscription');
        }?>
    </section>
    <?php } // end for
}


function aw_my_dashboard_content() {
    $user = $atts['user'] ? $atts['user'] : get_current_user_id();
    ?>
    <h1>My Dashboard</h1>
    <div id="my-dashboard">
        <?php aw_my_dashboard_get_section_content($user); ?>
    </div>
    <?php

} // end func my dashboard content
add_action('woocommerce_account_' . AW_MY_DASHBOARD_ENDPOINT . '_endpoint', 'aw_my_dashboard_content');
