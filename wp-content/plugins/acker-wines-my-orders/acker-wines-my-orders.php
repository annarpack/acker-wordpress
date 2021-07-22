<?php
/*
Plugin Name: Acker Wines // My Orders
Plugin URI: https://www.ackerwines.com/
Description: Acker Wines WooCommerce
Version: 1.0
Author: Acker Wines // Yair & Anna
Author URI: https://www.ackerwines.com/
*/
const AW_MY_ORDERS_ENDPOINT = 'my-orders';

function aw_my_orders_init() {
    aw_shared_plugin_init();
    add_rewrite_endpoint(AW_MY_ORDERS_ENDPOINT, EP_ROOT | EP_PAGES);
    wp_enqueue_script('aw-jquery-ui-js');
    wp_enqueue_script('aw-jquery-dataTables-js');
    wp_enqueue_style('aw-jquery-ui-css-1.12.1');
    wp_enqueue_style('aw-jquery-dataTables-css');
}
add_action('init', 'aw_my_orders_init');

function aw_my_orders_account_menu_items($items) {
    //there is already an end point for orders called "orders" with the url /my-account/orders
    $items[AW_MY_ORDERS_ENDPOINT] = __('My Orders', 'acker_wines');
    return $items;
}
add_filter('woocommerce_account_menu_items', 'aw_my_orders_account_menu_items', 10, 1);

function aw_my_orders_content() {
    $user = $atts['user'] ? $atts['user'] : get_current_user_id();
    $customer_orders = get_posts(array(
        'numberposts' => -1,
        'meta_key'=> '_customer_user',
        'meta_value'=> $user,
        'post_type' => wc_get_order_types(),
        'post_status' => array_keys(wc_get_order_statuses()),
    ));
    if ($customer_orders) {
    ?>
        <p>Click on the Order # to see the overall order details. Click on a column header to sort by it.</p>

        <table id="aw-my-products-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;">
        <thead>
            <tr class="woocommerce-orders-table__header" >
                <th class="woocommerce-orders-table__header aw-order-number"><span class="nobr">Order #</span></th>
                <th class="woocommerce-orders-table__header aw-order-date"><span class="nobr">Order Date</span></th>
                <th class="woocommerce-orders-table__header aw-order-total"><span class="nobr">Order Total</span></th>
                <th class="woocommerce-orders-table__header aw-order-status"><span class="nobr">Order Status</span></th>
                <th class="woocommerce-orders-table__header aw-order-actions"><span class="nobr">Actions</span></th>
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
        ); ?>
        <tr class="woocommerce-orders-table__row">
            <td class="woocommerce-orders-table__cell aw-order-number">
                <a href="<?php echo $order_url; ?>"><?php echo $order_number; ?></a>
            </td>
            <td class="woocommerce-orders-table__cell aw-order-date"><?php echo $order_date; ?></td>
            <td class="woocommerce-orders-table__cell aw-order-total"><?php echo $order_total ?></td>
            <td class="woocommerce-orders-table__cell aw-order-status"><?php echo $order_status; ?></td>
            <td class="woocommerce-orders-table__cell aw-order-actions"><?php echo $details_button_url; ?></td>
        </tr>
        <?php
        }// for each customer order
        ?>
        </tbody></table>

        <script type="text/javascript">
        <!--
        var table = jQuery("#aw-my-products-table").DataTable({
            "responsive": true,
            "scrollY": '770px',
            "order": [[0, "desc"]],
            "paging": false,
            "bLengthChange": true,
            "bFilter": true
        });
        -->
        </script>
    <?php
    } // if $customer_orders
} // end func my orders content
add_action('woocommerce_account_' . AW_MY_ORDERS_ENDPOINT . '_endpoint', 'aw_my_orders_content');
