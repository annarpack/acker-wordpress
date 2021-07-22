<?php
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
    AckerWines\AW_Purchase_History::aw_purchase_history_filter_buttons();
    ?>
    <h1>Invoices</h1>
        <table id="aw-my-orders-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" cellspacing="0"  width="100%" style="margin-top: 0; margin-bottom: 0;">
        <thead>
            <tr class="woocommerce-orders-table__header" >
                <th class="woocommerce-orders-table__header aw-order-date"><span class="nobr">Order Date</span></th>
                <th class="woocommerce-orders-table__header aw-order-number"><span class="nobr">Order #</span></th>
                <th class="woocommerce-orders-table__header aw-order-type"><span class="nobr">Order Type</span></th>
                <th class="woocommerce-orders-table__header aw-order-total"><span class="nobr">Order Total</span></th>
                <th class="woocommerce-orders-table__header aw-order-status"><span class="nobr">Order Status</span></th>
                <th class="woocommerce-orders-table__header aw-order-actions"><span class="nobr">Order Details</span></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($customer_orders as $customer_order) {
                $order = wc_get_order($customer_order);
                $order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));

                foreach ($order_items as $item_id => $item) {
                    $data = AckerWines\AW_Purchase_History::aw_purchase_history_get_invoice_data($order, $item);
                    $type = $data['product-type'];
                    $order_type_title =  AckerWines\AW_Purchase_History::aw_purchase_history_get_order_type($type);
                    ?>
                    <tr class="woocommerce-orders-table__row">
                        <td class="woocommerce-orders-table__cell aw-order-date"><?php echo $data['order-date']; ?></td>
                        <td class="woocommerce-orders-table__cell aw-order-number">
                            <a href="<?php echo $data['order-url']; ?>"><?php echo $data['order-number']; ?></a>
                        </td>
                        <td class="woocommerce-orders-table__cell aw-order-type"><?php echo $order_type_title ?></td>
                        <td class="woocommerce-orders-table__cell aw-order-total"><?php echo $data['order-total'] ?></td>
                        <td class="woocommerce-orders-table__cell aw-order-status"><?php echo $data['order-status'] ?></td>
                        <td class="woocommerce-orders-table__cell aw-order-actions"><?php echo $data['order-button'] ?></td>
                    </tr>
                    <?php
                    }
                }
                ?>
        </tbody>
    </table>

    <script type="text/javascript">
        var table = jQuery("#aw-my-orders-table").DataTable({
            "responsive": true,
            "scrollY": '770px',
            "order": [[1, "desc"]],
            "paging": false,
            "bLengthChange": true,
            "bFilter": true
        });
    </script>
<?php
}
?>
