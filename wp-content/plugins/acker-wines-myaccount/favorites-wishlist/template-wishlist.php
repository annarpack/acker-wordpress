<?php

function aw_wishlist_page_get_shop_loop(){
	?>
	<div class="product-inner clr"  v-bind:id="product.id">
	<ul class="woo-entry-inner clr" >
		<li class="image-wrap">
			<button class="aw-remove-from-wishlist" >
				<i class="fa fa-times-circle" :product-sku="product.sku"  :product-id="product.id"  ></i>
			</button>
				 <div class="woo-entry-image clr">
						 <a v-bind:href="product.link" class="woocommerce-LoopProduct-link no-lightbox">
								 <img :src="product.thumb" v-bind:alt="product.title" class="woo-entry-image-main" itemprop="image" />
							</a>
				 </div>
				 <a :href="'/shop/?add-to-cart=' + product.id"
				 data-quantity="1"
				 class="button product_type_simple add_to_cart_button ajax_add_to_cart"
				 :data-product_id="product.id"
				 :data-product_sku="product.sku"
				 :aria-label="'Add' + product.title + 'to your cart'"
				 rel="nofollow">
				 Add to cart</a>
		</li>
		<ul class="woo-product-info">
			<li class="title">
						<a v-bind:href="product.link">
							 <div v-html="product.title"></div>
						</a>
			</li>
			<li class="price-wrap">
					<div v-html="product.price"></div>
			</li>
		</ul>
	</ul></div>
	<?php
}
function aw_wishlist_page_get_auction_loop(){
	?>
	<div class="product-inner clr"  v-bind:id="product.id">
	<ul class="woo-entry-inner clr" >
		<li class="image-wrap">
			<button class="aw-remove-from-wishlist" >
				<i class="fa fa-times-circle" :product-sku="product.sku"  :product-id="product.id"  ></i>
			</button>
				 <div class="woo-entry-image clr">
						 <a v-bind:href="product.link" class="woocommerce-LoopProduct-link no-lightbox">
								 <img :src="product.thumb" v-bind:alt="product.title" class="woo-entry-image-main" itemprop="image" />
							</a>
				 </div>
				 <a :href="product.link"
				 data-quantity="1"
				 :data-product_id="product.id"
				 :data-product_sku="product.sku"
				 class="button add_to_cart_button"
				 :aria-label="'Bid on ' + product.title "
				 rel="nofollow"
				 >
				 Bid Now</a>
		</li>
		<ul class="woo-product-info">
			<li class="title">
						<a v-bind:href="product.link">
							 <div v-html="product.title"></div>
						</a>
			</li>
			<li class="price-wrap">
					<div v-html="product.price"></div>
			</li>
		</ul>
	</ul></div>
	<?php
}
function aw_wishlist_get_template() {
	echo '<h1 class="page-title">Wine Wishlist</h1>';
	$user = get_current_user_id();
	include_once(plugin_dir_path( __FILE__ ) . '../page-loading.php');
	echo '<div id="wishlist-content">';

	if(is_plugin_active('yith-woocommerce-wishlist-premium/init.php')){
    global $yith_wcwl;
    $yith_wcwl = YITH_WCWL();

    $wishlists = YITH_WCWL()->get_wishlists( array( 'user_id' => $user, 'wishlist_visibility' => 'public' ) );
    $user_meta = get_user_meta( $user );
		if(!empty($wishlists)){
			$wishlist_token = $wishlists[0]['wishlist_token'];
			$wishlist = $wishlists[0];
			$wishlist_id = $wishlist['ID'];
			$wishlist_meta = YITH_WCWL()->get_wishlist_detail_by_token( $wishlist_id );
		}
		echo '<div id="aw-list-view-buttons">
			<button><i class="icon-grid aw-template-switch-button" aw-container-type="items" ></i></button>
			<button><i class="icon-list aw-template-switch-button" aw-container-type="table"></i></button>
		</div><BR />';

    if( ! empty( $wishlists ) ){

			?>
			<div id="aw-account-table-container" class="aw-account-container" style="display:none;">
		    <table id="aw-wishlist-table" class="aw-datatables woocommerce-orders-table " cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;">
				<span v-html="products"></span>
		        <thead>
		            <tr>
									<th class="woocommerce-orders-table__header  aw-product-sku"><span class="nobr">SKU</span></th>
									<th class="woocommerce-orders-table__header  aw-product-fmt"><span class="nobr">Fmt</span></th>
									<th class="woocommerce-orders-table__header  aw-product-vintage"><span class="nobr">Vintage</span></th>
									<th class="woocommerce-orders-table__header  aw-product-name"><span class="nobr">Wine Name</span></th>
									<th class="woocommerce-orders-table__header  aw-product-des"><span class="nobr">Designation</span></th>
									<th class="woocommerce-orders-table__header  aw-product-prod"><span class="nobr">Producer</span></th>
									<th class="woocommerce-orders-table__header  aw-product-cart"><span class="nobr">Add to Cart</span></th>
									<th class="woocommerce-orders-table__header  aw-product-remove"><span class="nobr">Remove</span></th>
		            </tr>
		        </thead>
			    <tbody v-for="product in products">
						<tr class="woocommerce-orders-table__row aw-product-order" :data-row-id="product.id"  >
								<td class="woocommerce-orders-table__cell  aw-product-sku"></td>
								<td class="woocommerce-orders-table__cell  aw-product-fmt"></td>
								<td class="woocommerce-orders-table__cell  aw-product-vintage"></td>
								<td class="woocommerce-orders-table__cell  aw-product-name"></td>
								<td class="woocommerce-orders-table__cell  aw-product-des"></td>
								<td class="woocommerce-orders-table__cell  aw-product-prod"></td>
								<td class="woocommerce-orders-table__cell  aw-product-cart"></td>
								<td class="woocommerce-orders-table__cell  aw-product-remove"></td>
						</tr>
			    </tbody>
			</table>
		</div>
		<div id="aw-account-items-container" class="aw-account-container" display="none">
			<div class="woocommerce columns-4">
					<ul class="products oceanwp-row clr grid" >
							<li class="entry has-media col span_1_of_4 owp-content-center owp-thumbs-layout-horizontal owp-btn-normal owp-tabs-layout-horizontal has-no-thumbnails product type-product status-publish has-post-thumbnail taxable shipping-taxable purchasable product-type-simple"
									v-for="product in products" >
									<div v-if="product.product_type === 'retail' || product.product_type === 'fine-rare'">
										 <?php echo aw_wishlist_page_get_shop_loop(); ?>
								 </div>
								 <div v-if="product.product_type === 'auction'">
										 <?php echo aw_wishlist_page_get_auction_loop(); ?>
								 </div>
							</li>
					</ul>
				</div>
		</div>

		<?php do_action( 'yith_wcwl_before_wishlist_form', $wishlist_meta );		?>
		<form id="yith-wcwl-form" action="<?php //echo $form_action ?>" method="post" class="woocommerce">
		  <?php wp_nonce_field( 'yith-wcwl-form', 'yith_wcwl_form_nonce' );
		  do_action( 'yith_wcwl_before_wishlist', $wishlist_meta ); ?>
			<table class="shop_table cart wishlist_table"
			data-pagination="no"
			data-per-page="5"
			:data-id="<?php echo $wishlist_id; ?>"
			data-token="<?php echo $wishlist_token; ?>" >
		        <tbody v-for="product in products">
                <tr :id="product.id" :data-row-id="product.id">
									<td class="product-checkbox">
                    <input type="checkbox" :value="product.id" name="add_to_cart[]" />
                  </td>
                  <td class="product-remove">
                      <div>
                          <a :href="product.remove_url" :data-id="product.id"
													class="remove remove_from_wishlist"
													title="<?php echo apply_filters( 'yith_wcwl_remove_product_wishlist_message_title',__( 'Remove this product', 'yith-woocommerce-wishlist' )); ?>">X</a>
                      </div>
                  </td>
                </tr>
		        </tbody>
		    </table>
		    <?php wp_nonce_field( 'yith_wcwl_edit_wishlist_action', 'yith_wcwl_edit_wishlist' ); ?>
		    <input type="hidden" value="<?php echo $wishlist_token ?>" name="wishlist_id" id="wishlist_id">
		    <?php do_action( 'yith_wcwl_after_wishlist', $wishlist_meta ); ?>
		</form>
	</div>
		<?php do_action( 'yith_wcwl_after_wishlist_form', $wishlist_meta ); ?>

	<?php
		}// if there are products in wishlist
		else {
			echo '<p>You have no wishlist products.</p>';
		}
	} // if plugin is active
	else {
		echo '<p>Please activate YITH Wishlist Plugin.</p>';
	}
	echo '</div>';
}
