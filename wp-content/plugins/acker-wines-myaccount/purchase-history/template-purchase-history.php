<?php
function aw_purchase_history_get_template(){
 ?>
 <h1 class="aw-page-title">Purchase History</h1>
 <?php include_once(plugin_dir_path( __FILE__ ) . '../page-loading.php'); ?>
 <?php echo AckerWines\AW_Purchase_History::aw_purchase_history_filter_buttons(); ?>
 <div id="purchase-history-vue-root">
    <table id="aw-purchase-history-table" style="display: none;">
        <thead>
            <tr class="" >
                <th class="aw-order-type"><span class="nobr">Order Type</span></th>
                <th class="aw-order-date"><span class="nobr">Date</span></th>
                <th class="aw-order-number"><span class="nobr">Paddle / Invoice#</span></th>
                <th class="aw-order-total"><span class="nobr">Total</span></th>
                <th class="aw-order-status"><span class="nobr">Payment<br/>Status</span></th>
                <th class="aw-order-ship"><span class="nobr">Shipping<br/>Status</span></th>
                <th class="aw-order-track"><span class="nobr">Track</span></th>
								<th class="aw-order-details"><span class="nobr">Details</span></th>
            </tr>
        </thead>
        <tbody>
            <tr class="" v-for="order in orders">
                <td class="aw-order-type">{{ order.order_type }}</td>
                <td class="aw-order-date">{{ order.order_date }}</td>
                <td class="aw-order-number">{{ order.order_number }}</td>
                <td class="aw-order-total"><span class="aw-order-total" v-html="order.order_total"></span></td>
								<td class="aw-order-status"><b class="aw-order-status">{{ order.payment_status }}</b></td>
                <td class="aw-order-ship"><span v-html="order.ship"></span></td>
                <td class="aw-order-track">
										<a :href="order.tracking_link">Track</a>
								</td>
								<td class="aw-order-details">{{ order.order_details }}</td>
            </tr>

        </tbody>
    </table>
</div>
<?php
}
