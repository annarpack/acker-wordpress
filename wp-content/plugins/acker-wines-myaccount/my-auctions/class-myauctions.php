<?php
if(!class_exists('AW_MyAuctions')){
	class AW_MyAuctions {
		function aw_myauctions_get_order_type($order_type){
		    $order_type_array = ['shop_order', 'shop_subscription', 'tickets'];
		    $order_titles_array = ['Retail Order', 'Subscription', 'Event Tickets'];
		    $index = array_search($order_type, $order_type_array);
		    $type_title = $order_titles_array[$index];
		    return $type_title;
		}


	}
}
