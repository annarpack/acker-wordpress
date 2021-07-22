type="text/javascript";
jQuery(document).ready(function($) {
	const pageLoading = $('#page-loading');
	const allComplete = $('#aw-dashboard-all-complete');
	const dashboardVueRoot = $('#dashboard-vue-root');

	const auctionInitNum = '0';

	const getTodaysDate = () => {
		let date = new Date();
		let y = date.getFullYear();
		let month = date.getMonth();
		if(month < 4){
			y = y - 1;
		}
		let d = date.getDate();
		let m = month + 1;
		if(m < 10){
			m = '0' + m;
		}
		if(d < 10){
			d = '0'+ d;
		}
		let newDate = `${y}-${m}-${d}`;
		//console.log('newDate', newDate);
		return newDate
	}

	const dateFrom = getTodaysDate();
	//const dateFrom = '2019-01-01';

	// const addLoading = (parent) => {
	// 		// add loading information box
	// 		if( pageLoading.css('display') !== 'block' ){
	// 				if( pageLoading.css('display') === 'none' ){
	// 						pageLoading.css('display', 'block');
	// 						pageLoading.show();
	// 				} else {
	// 						const loading = document.createElement('div');
	// 						$(loading).attr('id', 'loading');
	// 						const text = document.createElement('p');
	// 						$(text).html('  Loading, please wait ...  ');
	// 						$(loading)[0].appendChild(text);
	// 						$(parent)[0].appendChild(loading);
	// 				}
	// 		}
	// }

	const noOrders = () => {
		let error = document.createElement('div');
		$(error).attr('id', 'aw-no-orders');
		let text = document.createElement('h1');
		$(text).html('You have no orders.');
		$(error).appendChild(text);
		dashboardVueRoot.appendChild(error);
	};

	const beforeSendCall = () => {
		let parent = pageLoading;
		//addLoading(parent);
		pageLoading.show();
		//pageLoading.show();
		dashboardVueRoot.hide();
	};

	const invoiceDownloadCall = (paddleID, spinner) => {
        let a = document.createElement('a');
        a.href = '/api/auction-invoice.php?paddle=' + paddleID;
        document.body.append(a);
        a.click();
        a.remove();
        $(spinner).removeClass('show');
	};

	const dashboardAjaxCall = () => {
		acker.api.getProgInvoiceHeaders((response, success, xhr) => {
			console.log('getInvoiceHeaders response' , response );
		});
		acker.api.getProgConsignorSales(null, (response, success, xhr) =>{
			console.log('prog consignor response', response);
			//console.log('response.data', response.data);
		});
		acker.api.getDashboard(beforeSendCall, (response, success, xhr) => {
			console.log('getDashboard response' , response );
			if(success){
				//console.log(' response.data' , response.data );
				pageLoading.hide();
				//$('#dashboard-vue-root').show();
				if(response.data.recent === undefined &
					response.data.payments === undefined &
					response.data.shipped === undefined &
					response.data.unpaid === undefined
					){
					noOrders();
				}
				else {
					dashboardVueRoot.show();
					new Vue({
						el: "#dashboard-vue-root",
						data: {
							orders: {
								totalDue: response.data.totalDue,
								totalDueDisplay: response.data.totalDueDisplay,
								//pending: response.data.pending,
								unpaid: response.data.unpaid,
								payments: response.data.payments,
								shipped: response.data.shipped,
								recent: response.data.recent,
								auctions: response.data.auctions
							}
						}
					});
				}
				$('#appraisal-submit-form').hide();
				$('#appraisal-submit-button').click(e => {
					$('#appraisal-submit-form').toggle();
				});

				$('button.aw-invoice-download-button.aw-button.aw-red-button').click(e => {
					//console.log(' clicked download button ' );
					let paddleID = $(e.target).attr('data-paddleID');
					//console.log('paddleID ' ,  paddleID);
					let parent = $(e.target).parent()[0];
					let child = $(parent).children('i');
					if(child){
						$(child).addClass('show');
					}
					invoiceDownloadCall(paddleID, child);
				});

			}


		});
	}
	pageLoading.show();
	dashboardAjaxCall();


});
