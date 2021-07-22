<?php
const AW_MY_FAVORITES_ENDPOINT = 'my-favorites';

function aw_my_favorites_init() {
    aw_shared_plugin_init();
    add_rewrite_endpoint(AW_MY_FAVORITES_ENDPOINT, EP_ROOT | EP_PAGES);
    wp_enqueue_script('aw-jquery-ui-js');
    wp_enqueue_script('aw-jquery-dataTables-js');
    wp_enqueue_style('aw-jquery-ui-css-1.12.1');
    wp_enqueue_style('aw-jquery-dataTables-css');
    wp_enqueue_style('aw-plugins-css');
}
add_action('init', 'aw_my_favorites_init');

function aw_my_favorites_account_menu_items($items) {
    $items[AW_MY_FAVORITES_ENDPOINT] = __('My Favorites', 'acker_wines');
    return $items;
}
add_filter('woocommerce_account_menu_items', 'aw_my_favorites_account_menu_items', 10, 1);


function aw_my_favorites_content() {

    $user = $atts['user'] ? $atts['user'] : get_current_user_id();
    $user_meta = get_user_meta( $user );

    $favorites_list = tinv_wishlist_get();
    $fav_id = $favorites_list['ID'];
    $is_owner = $favorites_list['is_owner'];
    $favorites_items = tinvwl_get_wishlist_products( $fav_id );
    //echo '<p>' . var_dump($favorites_items) . '</p>';

    if( ! empty( $favorites_list ) && $is_owner == true ):
    ?>
    <h1 class="page-title">Favorite Wines</h1>

    <table id="aw-my-favorites-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;" >
        <thead>
            <tr>
                <th class="woocommerce-orders-table__header  aw-order-date"><span class="nobr">Date Added</span></th>
                <th class="woocommerce-orders-table__header  aw-product-name"><span class="nobr">Product</span></th>
                <th class="woocommerce-orders-table__header  aw-product-total"><span class="nobr">Product Total</span></th>
                <th class="woocommerce-orders-table__header  aw-product-price"><span class="nobr">Current Price</span></th>
                <th class="woocommerce-orders-table__header  aw-product-actions"><span class="nobr">Actions</span></th>
            </tr>
        </thead>
    <tbody>
    <?php endif; ?>
    <?php
    foreach( $favorites_items as $item ) {
        //echo '<p>' . var_dump($item) . '</p>';
        global $product;
        $date_added = date_i18n( get_option( 'date_format' ), strtotime( $item['dateadded'] ) );
        $product = wc_get_product( $item['product_id'] );
        $availability = $product->get_availability();
        $stock_status = isset( $availability['class'] ) ? $availability['class'] : false;
        $product_data = $product->get_data();
        $product_name = $product->get_name();
        $current_price = $product->get_price_html();
        $product_total = wc_price($product->get_price());
        $is_visible = $product && $product->is_visible();
        $product_permalink = get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ));
        $add_button_url = !($product->id && $product->is_purchasable()) ? '' : sprintf(
            '<a href="%s" rel="nofollow" data-product_id="%s" class="button %s product_type_%s">%s</a>',
            esc_url($product->add_to_cart_url()),
            esc_attr($product->id),
            'add_to_cart_button',
            esc_attr($product->product_type),
            esc_html($product->add_to_cart_text())
        ); ?>
        <tr class="woocommerce-orders-table__row aw-product-order">
            <td class="woocommerce-orders-table__cell aw-wish-date"><?php echo $date_added; ?></td>
            <td class="woocommerce-orders-table__cell aw-wish-name">
                <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>">
                    <?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?>
                </a><br/>
            </td>
            <td class="woocommerce-orders-table__cell aw-wish-total"><?php echo $stock_status; ?></td>
            <td class="woocommerce-orders-table__cell aw-wish-price"><?php echo $current_price; ?></td>
            <td class="woocommerce-orders-table__cell aw-wish-actions"><?php echo $add_button_url; ?></td>
        </tr>
    <?php
            //}// for each order item
    } // end for each customer order ?>
    </tbody></table>

    <script type="text/javascript">
        <!--
        var table = jQuery("#aw-my-favorites-table").DataTable({
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
    //} // if $customer_orders
}
add_action('woocommerce_account_' . AW_MY_FAVORITES_ENDPOINT . '_endpoint', 'aw_my_favorites_content');
