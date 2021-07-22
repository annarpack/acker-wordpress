<?php

function aw_purchase_history_return_products($filter){
    echo '<p>' . var_dump($filter) . '</p>';
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
        <h1><?php echo AckerWines\AW_Purchase_History::aw_purchase_history_get_filter_title($filter); ?></h1>
        <table id="aw-my-products-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;" >
            <thead>
                <tr>
                    <th class="woocommerce-orders-table__header aw-order-number"><span class="nobr">Order #</span></th>
                    <th class="woocommerce-orders-table__header  aw-product-type"><span class="nobr">Order Type</span></th>
                    <th class="woocommerce-orders-table__header  aw-product-name"><span class="nobr">Product Name</span></th>
                    <th class="woocommerce-orders-table__header  aw-product-total"><span class="nobr">Paid Price</span></th>
                    <th class="woocommerce-orders-table__header  aw-product-price"><span class="nobr">Current Price</span></th>
                    <th class="woocommerce-orders-table__header  aw-product-actions"><span class="nobr">Actions</span></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($customer_orders as $customer_order) {
                    $order = wc_get_order($customer_order);
                    $order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));

                    foreach ($order_items as $item_id => $item) {
                        if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
                            continue;
                        }
                        $data = AckerWines\AW_Purchase_History::aw_purchase_history_get_products_data($order, $item);
                        $type = $data['product-type'];
                        $order_type_title =  AckerWines\AW_Purchase_History::aw_purchase_history_get_order_type($type);
                        if($type == $filter){
                            ?>
                            <tr class="woocommerce-orders-table__row aw-product-order">
                                <td class="woocommerce-orders-table__cell aw-order-number"><a href="<?php echo $data['order-url']; ?>"><?php echo $data['order-number']; ?></a></td>
                                <td class="woocommerce-orders-table__cell aw-product-type"><?php echo $data['product-type']; ?></td>
                                <td class="woocommerce-orders-table__cell aw-product-name"><?php $data['product-name']; ?></td>
                                <td class="woocommerce-orders-table__cell aw-product-total"><?php echo $data['product-total']; ?></td>
                                <td class="woocommerce-orders-table__cell aw-product-price"><?php echo $data['product-price']; ?></td>
                                <td class="woocommerce-orders-table__cell aw-product-actions"><?php echo $data['product-button']; ?></td>
                            </tr>
                            <?php
                            }
                        }
                    }
                    ?>
            </tbody>
        </table>

        <script type="text/javascript">
            <!--
            var table = jQuery("#aw-my-products-table").DataTable({
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
} // end func
?>
