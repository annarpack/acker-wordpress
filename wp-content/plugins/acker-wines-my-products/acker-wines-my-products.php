<?php
/*
Plugin Name: Acker Wines // My Products
Plugin URI: https://www.ackerwines.com/
Description: Acker Wines WooCommerce
Version: 1.0
Author: Acker Wines // Yair
Author URI: https://www.ackerwines.com/
*/

const AW_MY_PRODUCTS_ENDPOINT = 'my-products';

function aw_my_products_init() {
    aw_shared_plugin_init();
    add_rewrite_endpoint(AW_MY_PRODUCTS_ENDPOINT, EP_ROOT | EP_PAGES);
    wp_enqueue_script('aw-jquery-ui-js');
    wp_enqueue_script('aw-jquery-dataTables-js');
    wp_enqueue_style('aw-jquery-ui-css-1.12.1');
    wp_enqueue_style('aw-jquery-dataTables-css');
}
add_action('init', 'aw_my_products_init');

function aw_my_products_account_menu_items($items) {
    $items[AW_MY_PRODUCTS_ENDPOINT] = __('My Products', 'acker_wines');
    return $items;
}
add_filter('woocommerce_account_menu_items', 'aw_my_products_account_menu_items', 10, 1);

function aw_my_products_content() {
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

    <table id="aw-my-products-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;" >
        <thead>
            <tr>
                <th class="woocommerce-orders-table__header  aw-order-number"><span class="nobr">Order #</span></th>
                <th class="woocommerce-orders-table__header  aw-order-date"><span class="nobr">Date</span></th>
                <th class="woocommerce-orders-table__header  aw-product-name"><span class="nobr">Product</span></th>
                <th class="woocommerce-orders-table__header  aw-product-total"><span class="nobr">Product Total</span></th>
                <th class="woocommerce-orders-table__header  aw-product-price"><span class="nobr">Current Price</span></th>
                <th class="woocommerce-orders-table__header  aw-product-actions"><span class="nobr">Actions</span></th>
            </tr>
        </thead>
    <tbody>
    <?php
    foreach ($customer_orders as $customer_order) {
        $order = wc_get_order($customer_order);

        $order_url = $order->get_view_order_url();
        $order_number = $order->get_order_number();
        $order_date = wc_format_datetime($order->get_date_created());
        $order_total = $order->get_formatted_order_total();
        $item_count = $order->get_item_count();
        $order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));

        foreach ($order_items as $item_id => $item) {
            if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
                continue;
            }

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
                esc_html($product->add_to_cart_text())
            ); ?>
            <tr class="woocommerce-orders-table__row aw-product-order">
                <td class="woocommerce-orders-table__cell aw-order-number">
                    <a href="<?php echo $order_url; ?>"><?php echo $order_number; ?></a>
                </td>
                <td class="woocommerce-orders-table__cell aw-order-date"><?php echo $order_date; ?></td>
                <td class="woocommerce-orders-table__cell aw-product-name"><?php echo $product_name . $product_qty; ?></td>
                <td class="woocommerce-orders-table__cell aw-product-total"><?php echo $product_total; ?></td>
                <td class="woocommerce-orders-table__cell aw-product-price"><?php echo $current_price; ?></td>
                <td class="woocommerce-orders-table__cell aw-product-actions"><?php echo $add_button_url; ?></td>
            </tr>
            <?php
            }// for each order item
    } // end for each customer order ?>
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
}
add_action('woocommerce_account_' . AW_MY_PRODUCTS_ENDPOINT . '_endpoint', 'aw_my_products_content');
