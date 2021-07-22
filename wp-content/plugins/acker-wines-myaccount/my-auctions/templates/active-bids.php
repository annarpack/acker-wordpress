<?php
function aw_account_myauctions_active_bids_get_template(){
?>
<h1>My Auctions > Active Bids</h1>
<span>Active bids for LIVE AUCTIONS may be amended via the "Edit Bid" links below, up until two hours before the sale begins. For adjustments after this time, please contact the auction administrator at XXXXX. </span>
<div id="aw-myauctions-active-bids-vue-root">
	<table id="aw-myauctions-bid-history-table"
	class="aw-auctions-table">
	<h4 v-for=""></h4>
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
			<tr class="" v-for="order in orders">
					<td class="aw-order-type">{{ order.order_type }}</td>
					<td class="aw-order-date">{{ order.order_date }}</td>
					<td class="aw-order-number">{{ order.order_link }}</a></td>
					<td class="aw-order-total"><span v-html="order.order_total"></span></td>
					<td class="aw-order-status"><b>{{ order.payment_status }}</b></td>
					<td class="aw-order-ship"><span v-html="order.ship"></span></td>
					<td class="aw-order-track">
							<a :href="order.tracking_link" class="button order_details_button">Track</a>
					</td>
					<td class="aw-order-details">{{ order.order_details }}</td>
			</tr>

	</tbody>
</table>
</div>

<?php
}?>
