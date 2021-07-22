<?php
function aw_favorites_page_get_shop_loop(){
	?>
	<div class="product-inner clr"  v-bind:id="product.id">
		<ul class="woo-entry-inner clr" >
			<li class="image-wrap">
				<div class="aw-tinv-wishlist-archive-product">
					<ul class="woo-entry-buttons">
						<li class="woo-wishlist-btn">
							<div class="tinv-wraper woocommerce tinv-wishlist tinvwl-shortcode-add-to-cart" :product-id="product.id">
								<button class="aw-remove-from-wishlist" :product-id="product.id">
									<a href="javascript:void(0)" class="tinvwl_add_to_wishlist_button tinvwl-icon-heart no-txt  tinvwl-position-shortcode inited-add-wishlist tinvwl-product-in-list tinvwl-product-make-remove"
									data-tinv-wl-list="[{}]"
									:data-tinv-wl-product="product.id"
									data-tinv-wl-productvariation="0"
									data-tinv-wl-producttype="simple"
									data-tinv-wl-action="remove"
									:product-id="product.id"
									:product-sku="product.sku"
									rel="nofollow"
									>
								</a>
							</button>
						</div>
						</li>
					</ul>
				</div>
					 <div class="woo-entry-image clr">
						 <!-- <button class="aw-remove-from-wishlist" :product-sku="product.sku"  :product-id="product.id" >
								<i class="ftinvwl ftinvwl-times" :product-id="product.id" ></i>
							</button> -->
							 <a v-bind:href="product.link" class="woocommerce-LoopProduct-link no-lightbox">
									 <img v-bind:src="product.thumb" v-bind:alt="product.title" class="woo-entry-image-main" itemprop="image" />
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

	 </ul>
	</div>
<?php
}
function aw_favorites_page_get_auction_products_loop(){
	?>
	<div class="product-inner clr"  v-bind:id="product.id">
		<ul class="woo-entry-inner clr" >
			<li class="image-wrap">
				<div class="aw-tinv-wishlist-archive-product">
					<ul class="woo-entry-buttons">
						<li class="woo-wishlist-btn">
							<div class="tinv-wraper woocommerce tinv-wishlist tinvwl-shortcode-add-to-cart" :product-id="product.id">
								<button class="aw-remove-from-wishlist" :product-id="product.id">
									<a href="javascript:void(0)" class="tinvwl_add_to_wishlist_button tinvwl-icon-heart no-txt  tinvwl-position-shortcode inited-add-wishlist tinvwl-product-in-list tinvwl-product-make-remove"
									data-tinv-wl-list="[{}]"
									:data-tinv-wl-product="product.id"
									data-tinv-wl-productvariation="0"
									data-tinv-wl-producttype="simple"
									data-tinv-wl-action="remove"
									:product-id="product.id"
									:product-sku="product.sku"
									rel="nofollow"
									>
								</a>
							</button>
						</div>
						</li>
					</ul>
				</div>
					 <div class="woo-entry-image clr">
						 <!-- <button class="aw-remove-from-wishlist" :product-sku="product.sku"  :product-id="product.id" >
								<i class="ftinvwl ftinvwl-times" :product-id="product.id" ></i>
							</button> -->
							 <a v-bind:href="product.link" class="woocommerce-LoopProduct-link no-lightbox">
									 <img v-bind:src="product.thumb" v-bind:alt="product.title" class="woo-entry-image-main" itemprop="image" />
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

	 </ul>
	</div>
<?php
}

function aw_favorites_get_template() {
	echo '<h1 class="page-title">My Favorites</h1>';
	include_once(plugin_dir_path( __FILE__ ) . '../page-loading.php');
	wp_enqueue_script( 'tinvwl' );
  $user = get_current_user_id();
  $user_meta = get_user_meta( $user );
	//AckerWines\AW_Favorites::aw_favorites_filters();
	echo '<div id="favorites-content">';
	if(is_plugin_active('ti-woocommerce-wishlist/ti-woocommerce-wishlist.php')){
    $favorites_list = tinv_wishlist_get();
		//echo var_dump($favorites_list);
    $fav_id = $favorites_list['ID'];
    $is_owner = $favorites_list['is_owner'];
		$favorites_items = tinvwl_get_wishlist_products( $fav_id );
		if(!empty($favorites_items)){
			echo '<div id="aw-list-view-buttons">
				<button><i class="icon-grid aw-template-switch-button" aw-container-type="items" ></i></button>
				<button><i class="icon-list aw-template-switch-button" aw-container-type="table"></i></button>
			</div><BR />';
			?>
			<div id="aw-account-table-container" class="aw-account-container" style="display:none;">
		    <table id="aw-favorites-table" class="aw-datatables tinvwl-table-manage-list" cellspacing="0" width="100%" style="margin-top: 0; margin-bottom: 0;" >
		        <thead>
		            <tr>
		                <th class="woocommerce-orders-table__header  aw-product-sku"><span class="nobr">SKU</span></th>
										<th class="woocommerce-orders-table__header  aw-product-fmt"><span class="nobr">Fmt</span></th>
		                <th class="woocommerce-orders-table__header  aw-product-vintage"><span class="nobr">Vintage</span></th>
										<th class="woocommerce-orders-table__header product-name aw-product-name"><span class="nobr">Wine Name</span></th>
										<th class="woocommerce-orders-table__header  aw-product-des"><span class="nobr">Designation</span></th>
										<th class="woocommerce-orders-table__header  aw-product-prod"><span class="nobr">Producer</span></th>
		                <th class="woocommerce-orders-table__header product-action aw-product-cart"><span class="nobr">Add to Cart</span></th>
										<th class="woocommerce-orders-table__header product-remove aw-product-remove"><span class="nobr">Remove</span></th>
		            </tr>
		        </thead>
		    <tbody>
		        <tr class="woocommerce-orders-table__row aw-product-order">
								<td class="woocommerce-orders-table__cell  aw-product-sku"></td>
								<td class="woocommerce-orders-table__cell  aw-product-fmt"></td>
								<td class="woocommerce-orders-table__cell  aw-product-vintage"></td>
								<td class="woocommerce-orders-table__cell product-name aw-product-name"></td>
								<td class="woocommerce-orders-table__cell  aw-product-des"></td>
								<td class="woocommerce-orders-table__cell  aw-product-prod"></td>
								<td class="woocommerce-orders-table__cell  aw-product-cart"></td>
								<td class="woocommerce-orders-table__cell product-remove aw-product-remove"></td>
		        </tr>
		    </tbody>
			</table>
			</div>
			<div id="aw-account-items-container" class="aw-account-container" display="none">
				<div class="woocommerce columns-4">
						<ul class="products oceanwp-row clr grid" >
								<li class="entry has-media col span_1_of_4 owp-content-center owp-thumbs-layout-horizontal owp-btn-normal owp-tabs-layout-horizontal has-no-thumbnails product type-product status-publish has-post-thumbnail taxable shipping-taxable purchasable product-type-simple"
										v-for="product in products">

										<div v-if="product.product_type === 'retail' || product.product_type === 'fine-rare'">
											 <?php echo aw_favorites_page_get_shop_loop(); ?>
									 </div>
									 <div v-if="product.product_type === 'auction'">
											 <?php echo aw_favorites_page_get_auction_products_loop(); ?>
									 </div>


								</li>
						</ul>
					</div>
			</div>
			<?php $wl_paged = get_query_var( 'wl_paged' );
				$form_url = tinv_url_wishlist( $favorites_list['share_key'], $wl_paged, true ); ?>
			<form id="tinvwl-favorites-form" action="<?php echo esc_url( home_url() . '/my-account/favorites' ); ?>" method="post" autocomplete="off">
				<?php do_action( 'tinvwl_before_wishlist_table', $favorites_list ); ?>
				<table id="tinvwl-favorites-table" class="tinvwl-table-manage-list" >
					<tbody>
						<tr class="wishlist_item" v-for="product in products">
							<td class="product-cb">
									<input type="checkbox" name="wishlist_pr[]" :value="product.id" title="Select for bulk action" />
							</td>
							<td class="product-remove" >
								<button type="submit" name="tinvwl-remove"
										class="remove_from_favorites"
												:value="product.id"
												:data-id="product.id"
												title="Remove">
									<i class="ftinvwl ftinvwl-times"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
				<?php do_action( 'tinvwl_wishlist_contents_after' ); ?>
			</form>
		</div>
	    <?php
			do_action( 'tinvwl_after_wishlist', $favorites_list );
		} // end if is array
		else {
			echo '<p>You have no favorite products yet.';
		}
	} // if plugin is active
	else {
		echo '<p>Please activate TI Wishlist Plugin.</p>';
	}
	echo '</div>';
}
