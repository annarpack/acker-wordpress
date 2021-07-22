<?php
function aw_account_myauctions_consignments_get_template(){
?>
<h1>My Auctions > Consignments</h1>
<div>
		<table id="aw-myauctions-table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;">
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
