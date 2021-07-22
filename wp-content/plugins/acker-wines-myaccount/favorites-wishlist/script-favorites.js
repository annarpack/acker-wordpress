type="text/javascript";
jQuery(document).ready(function($) {
	//console.log('favorites connected');
	if($('body').hasClass('woocommerce-account')){
		const pageLoading = $('#page-loading');
		const favoritesTable = $('#aw-favorites-table');
		const favoritesContent = $('#favorites-content');
		const switchTemplateButton = $('.aw-template-switch-button');
		const viewSwitchButtons = $('#aw-list-view-buttons');
		const templateInput = $('input#template-id');
		const tableContainer = $('#aw-account-table-container');
		const itemsContainer = $('#aw-account-items-container');
		let favoritesDataTable  = new $.fn.dataTable.Api('#aw-favorites-table');
		
		// const addLoadingScreen = (loadingDiv) => {
		// 		// add loading information box
		// 		if( $('#loading-screen').css('display') !== 'block' ){
		// 				if( $('#loading-screen').css('display') === 'none' ){
		// 						$('#loading-screen').css('display', 'block');
		// 						$('#loading-screen').show();
		// 				} else {
		// 						const loadingScreen = document.createElement('div');
		// 						$(loadingScreen).attr('id', 'loading-screen');
		// 						const text = document.createElement('p');
		// 						$(text).html('  Loading, please wait ...  ');
		// 						$(loadingScreen)[0].appendChild(text);
		// 						$(loadingDiv)[0].appendChild(loadingScreen);
		// 				}
		// 		}
		// }
		$('#tinvwl-favorites-form').hide();

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


		const beforeGetDataCall = () => {
			// addLoadingScreen('#page-loading');
			// $('#page-loading').show();
			// $('#loading-screen').show();
			pageLoading.show();
			favoritesContent.hide();
		}
		const beforeRemoveCall = () => {
			//addLoadingScreen(favoritesContent);
			//$('#loading-screen').show();
			pageLoading.show();
		}

		const removeItemCall = (hiddenForm, productID, favoritesDataTable, favoritesItemsVue ) => {
			//console.log('remove item clicked');
			const hiddenRemoveButtons = $(hiddenForm).find('.remove_from_favorites');
			//console.log(' hiddenRemoveButtons' ,  hiddenRemoveButtons);hiddenRemoveButtons
			$(hiddenRemoveButtons).each((i, elm) => {
				if($(elm).attr('data-id') === productID){
					//console.log(' elm' , elm );
					let wishlistID = 0;
					acker.api.removeFavorite(productID, wishlistID, beforeGetDataCall, (response, success, xhr) => {
						console.log('response', response);
						//console.log('response data ', response.data);
						//location.reload(true);
						if(success){
							location.reload(true);
						}

					});
				}
			})
		}

		const favoritesAjaxCall = () => {
			acker.api.getFavorites(beforeGetDataCall, (response, success, xhr) => {
			console.log('response ', response);
				if(success){
					$('#loading-screen').hide();
					pageLoading.hide();
					$(viewSwitchButtons).show();
					clickSwitchButton($(switchTemplateButton)[0]);
					$(favoritesContent).show();
					if(response.data !== undefined){
						if(response.data.length === 0 && response.data !== null && response.data !== 'null'){
							//if there are no purchases
							$('#loading-screen').hide();
							$('#aw-favorites-table').hide();
							$(viewSwitchButtons).hide();
							let noPurchases = document.createElement('p');
							$(noPurchases).html('There are no items in your favorites list.');
							$(favoritesContent).html(noPurchases);
							$(favoritesContent).hide();
						}
						else {
							//console.log('response data ', response.data);
							$(favoritesContent).show();
							favoritesDataTable = $('#aw-favorites-table').DataTable({
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

							let favoritesDataVue =  new Vue({
								el: '#tinvwl-favorites-table',
								data: {
									products: response.data
								}
							});
							let favoritesItemsVue = new Vue({
			            el: '#aw-account-items-container',
			            data: {
										products: response.data
									}
			        });
							const hiddenForm = $('#tinvwl-favorites-form');
							let removeButtons = $('.aw-remove-from-wishlist');
							$(removeButtons).click(e => {
								//console.log(' remove button click' );
								let productID = $(e.target).attr('product-id');
								//console.log(' productID' ,  productID);
								removeItemCall(hiddenForm, productID,  favoritesDataTable, favoritesItemsVue);
							});
							$('.aw-add-to-cart-checkbox').click(e => {
								let productID = $(e.target).attr('product-id');
								//console.log('productID ' ,  productID);
							});
						}
					}
					else {
						//if there are no purchases
						$('#loading-screen').hide();
						$('#aw-favorites-table').hide();
						$(viewSwitchButtons).hide();
						let noPurchases = document.createElement('p');
						$(noPurchases).html('There are no items in your favorites list.');
						$(favoritesContent).html(noPurchases);
						$(favoritesContent).hide();
					}
				}
			}); // end ajax call
		} // end func
		pageLoading.show();
		favoritesAjaxCall();



	}
});
