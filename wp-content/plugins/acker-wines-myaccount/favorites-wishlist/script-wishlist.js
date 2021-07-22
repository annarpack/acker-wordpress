type="text/javascript";
jQuery(document).ready(function($) {
	//console.log('wishlist connected');
	if($('body').hasClass('woocommerce-account')){
		const pageLoading = $('#page-loading');
		const wishlistTable = $('#aw-wishlist-table');
		const wishlistContent = $('#wishlist-content');
		const tableContainer = $('#aw-account-table-container');
		const itemsContainer = $('#aw-account-items-container');
		const viewSwitchButtons = $('#aw-list-view-buttons');
		const switchTemplateButton = $('.aw-template-switch-button');
		//let removeButtons = $('.aw-remove-from-wishlist');

		let wishlistDataTable  = new $.fn.dataTable.Api('#aw-wishlist-table');
		// let wishlistDataTable = $('#aw-wishlist-table').DataTable({
		// 	columns: [
		// 		{data: 'sku'},
		// 		{data: 'format'},
		// 		{data: 'vintage'},
		// 		{data: 'title'},
		// 		{data: 'designation'},
		// 		{data: 'producer'},
		// 		{data: 'cart_button'},
		// 		{data: 'remove_button'},
		// 	],
		// 	responsive: true,
		// 	order: [[0, 'asc']],
		// 	paging: true,
		// 	pageLength: 10,
		// 	bLengthChange: true,
		// 	bFilter: false
		// })

		// const addLoadingScreen = (parent) => {
		// 		// add loading information box
		// 		if( $('#loading-screen').css('display') !== 'block' ){
		// 				if( $('#loading-screen').css('display') === 'none' ){
		// 						$('#loading-screen').css('display', 'block');
		// 						$('#loading-screen').show();
		// 				} else {
		// 						const loading = document.createElement('div');
		// 						$(loading).attr('id', 'loading-screen');
		// 						const text = document.createElement('p');
		// 						$(text).html('  Loading, please wait ...  ');
		// 						$(loading)[0].appendChild(text);
		// 						$(parent)[0].appendChild(loading);
		// 				}
		// 		}
		// }
		const beforeGetDataCall = () => {
			// let parent = $('#page-loading');
			// addLoadingScreen(parent);
			// $('#page-loading').show();
			// $('#loading-screen').show();
			pageLoading.show();
			wishlistContent.hide();
		}
		const beforeRemoveCall = () => {
			//addLoadingScreen(wishlistContent);
			//$('#loading-screen').show();
			pageLoading.show();
		}

		const clickSwitchButton = (target) => {
			let type = $(target).attr('aw-container-type');
			$('.aw-account-container').hide();
			$('#aw-account-' + type + '-container').show();
			$('.aw-template-switch-button').removeClass('active');
			$(target).addClass('active');
			//$(`#aw-account-${type}-container`).show();
		}
		$(switchTemplateButton).click(e => {
			clickSwitchButton(e.target);
		});

		const removeItemCall = (hiddenForm, productID, wishlistDataTable, wishlistItemsVue ) => {
			//console.log('remove item clicked');
			const hiddenRemoveButtons = $(hiddenForm).find('a.remove_from_wishlist');
			//console.log(' hiddenRemoveButtons' ,  hiddenRemoveButtons);hiddenRemoveButtons
			$(hiddenRemoveButtons).each((i, elm) => {
				if($(elm).attr('data-id') === productID){
					//console.log(' elm' , elm );
					$(elm).click();
						location.reload(true);
				}
			})
		}

		const wishlistAjaxCall = () => {
			acker.api.getWishlist(beforeGetDataCall, (response, success, xhr) => {
				console.log('response ', response );
				if(success){
					$(viewSwitchButtons).show();
					$('#loading-screen').hide();
					clickSwitchButton($(switchTemplateButton)[0]);
					if(response.data !== undefined && response.data !== null && response.data !== 'null'){
						if(response.data.length === 0){
							//if there are no purchases
							$('#aw-wishlist-table').hide();
							//$('#loading-screen').hide();
							viewSwitchButtons.hide();
							let noPurchases = document.createElement('p');
							$(noPurchases).html('There are no items in your wishlist.');
							wishlistContent.html(noPurchases);
							wishlistContent.show();
						}
						else {
							//console.log('response data ', response.data);
							//let wishlistDataTable  = new $.fn.dataTable.Api('#aw-wishlist-table');
							//wishlistDataTable.destroy();
							wishlistDataTable = $('#aw-wishlist-table').DataTable({
								data: response.data,
								columns: [
									{ data: 'sku' },
									{ data: 'format' },
									{ data: 'vintage' },
									{ data: 'name_link' },
									{ data: 'designation' },
									{ data: 'producer' },
									{ data: 'cart_button' },
									{ data: 'remove_button' }
								],
								responsive: true,
								order: [[1, 'desc']],
								paging: true,
								pageLength: 10,
								bLengthChange: true,
								bFilter: false,
							});

							let wishlistDataVue =  new Vue({
								el: '#yith-wcwl-form',
								data: {
									products: response.data
								}
							});

							let wishlistItemsVue = new Vue({
			            el: '#aw-account-items-container',
			            data: {
										products: response.data
									}
			        });
						 	let removeButtons = $('.aw-remove-from-wishlist');
							const hiddenForm = $('form#yith-wcwl-form');
							$(removeButtons).click(e => {
								//console.log(' remove button click' );
								let productID = $(e.target).attr('product-id');
								//console.log(' productID' ,  productID);
								removeItemCall(hiddenForm, productID, wishlistDataTable, wishlistItemsVue);
							});
							$(hiddenForm).hide();
							$('.aw-add-to-cart-checkbox').click(e => {
								let productID = $(e.target).attr('product-id');
								console.log('productID ' ,  productID);
							});
							$(wishlistContent).show();
						}
					}// end if length > 0
					else {
						//if there are no purchases
						$('#aw-wishlist-table').hide();
						$('#loading-screen').hide();
						viewSwitchButtons.hide();
						let noPurchases = document.createElement('p');
						$(noPurchases).html('There are no items in your wishlist.');
						wishlistContent.html(noPurchases);
						wishlistContent.show();
					}
					pageLoading.hide();
				}//end if success
			}); // end ajax call
		} // end func
		wishlistAjaxCall();



		}


});
