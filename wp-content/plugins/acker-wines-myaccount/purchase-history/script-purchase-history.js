type="text/javascript";
jQuery(document).ready(function($) {

	if($('body').hasClass('woocommerce-my-orders')){
    //console.log('purchase history connected');
		const pageLoading = $('#page-loading');
		const filtersSection = $('#purchase-history-filters-section');
		const typeFilterDiv = $(filtersSection).find('div.aw-type');
		const typeInput = $(filtersSection).find('input#purchase-history-filter-selection-type');
		const typeSelect = $(filtersSection).find('select.type-filter');
		const typeOptions = $(filtersSection).find('option');

		const yearFilterDiv = $(filtersSection).find('div.aw-year');
		const yearInput = $(filtersSection).find('input#purchase-history-filter-selection-year');
		const yearSelect = $(filtersSection).find('select.year-filter');

		const datesFilterDiv = $(filtersSection).find('div.aw-dates');
		const datesInputTo = $(filtersSection).find('input#purchase-history-filter-selection-dates-to');
		const datesInputFrom = $(filtersSection).find('input#purchase-history-filter-selection-dates-from');
		let historyDataTable = new $.fn.dataTable.Api("#aw-purchase-history-table");
		let maxDate = new Date();
		let fullYear = maxDate.getFullYear();
		let month = maxDate.getMonth();
		if(month < 4){
			fullYear = fullYear - 1;
		}
		let minDate = new Date(fullYear, 0, 1);
		//maxDate = new Date(maxDate, "MMM DD YYYY");
		//console.log(maxDate);
		$(datesInputTo).datepicker({
			dateFormat: "M d, yy",
			setDate: maxDate,
			showButtonPanel: true,
			buttonImage: "/images/datepicker.gif"
		});
		$(datesInputFrom).datepicker({
			dateFormat: "M d, yy",
			setDate: minDate,
			showButtonPanel: true,
			buttonImage: "/images/datepicker.gif"
		});
		$(datesInputTo).datepicker("setDate", maxDate);
		$(datesInputFrom).datepicker("setDate", minDate);

    const addLoading = (parent) => {
        // add loading information box
        if( $('#page-loading').css('display') !== 'block' ){
            if( $('#page-loading').css('display') === 'none' ){
                $('#page-loading').css('display', 'block');
                $('#page-loading').show();
            } else {
                const loading = document.createElement('div');
                $(loading).attr('id', 'loading');
                const text = document.createElement('p');
                $(text).html('  Loading, please wait ...  ');
                $(loading)[0].appendChild(text);
                $(parent)[0].appendChild(loading);
            }
        }
    }
		$("#aw-purchase-history-table").hide();

		const beforeSendCall = () => {
			let parent = $('#page-loading');
			addLoading(parent);
			$('#page-loading').show();
			$("#aw-purchase-history-table").hide();
			$('#purchase-history-filters-section').hide();
		}


		// const getYearColumnData = () => {
		// 	let years = [];
		// 	let filteredTable = historyDataTable
		// 			.column(1)
		// 			.data()
		// 			.filter( (value, index) => {
		// 				let date = new Date(value);
		// 				let year = date.getFullYear();
		// 				years[year] = 1;
		// 			});
		// 	let yearsKeys = Object.keys(years).sort().reverse();
		// 	if(yearsKeys.length > 0){
		// 		$(yearsKeys).each( (i, val) => {
		// 			let yearOption = document.createElement('option');
		// 			$(yearOption).text(val);
		// 			$(yearSelect)[0].appendChild(yearOption);
		// 		})
		// 	} // end if
		// }

		const findMin = () => {
			let filteredTable = historyDataTable
					.column(1)
					.data()
					.filter( (value, index) => {
						let num = new Date(value);
						num = num.getTime();
						let date = new Date(value);
						date = date.getTime();
						if(date < num){
							//console.log('min date', date);
							return date
						}

					});
		}

		const findMax = () => {
			let filteredTable = historyDataTable
					.column(1)
					.data()
					.filter( (value, index) => {
						if(index === 0){
							let date = new Date(value);
							date = date.getTime();
							//console.log('max date', date);
							return date
						}
					});
		};

		const invoiceDownloadCall = (paddleID, spinner) => {
            let a = document.createElement('a');
            a.href = '/api/auction-invoice.php?paddle=' + paddleID;
            document.body.append(a);
            a.click();
            a.remove();
            $(spinner).removeClass('show');
		};

		const setDownloadButtons = () => {
			$('.aw-invoice-download-button').click(e => {
				//console.log(' clicked download button ' );
				let paddleID = $(e.target).attr('data-paddleID');
				//console.log('paddleID ' ,  paddleID);
				let parent = $(e.target).parent()[0];
				let child = $(parent).children()[1];
				$(child).addClass('show');
				invoiceDownloadCall(paddleID, child);
			});
		}

    const purchaseHistoryAjaxCall = () => {
			acker.api.getProgInvoiceHeaders((response, success, xhr) => {
				console.log(' response' , response );
			});
      acker.api.getPurchaseHistory(beforeSendCall, (response, success, xhr) => {
				console.log(' response' , response );
        if(success){
					if(response.data[0] === undefined){
						$('#page-loading').hide();
						let noPurchases = document.createElement('p');
						$(noPurchases).html('There are no purchases in your purchase history.');
						$('#purchase-history-vue-root')[0].appendChild(noPurchases);
					}
					else if(response.data[0] !== null || response.data[0].length !== 0){
						//console.log('response data ', response.data);
						//if there are purchases, and the data is not null
						$('#purchase-history-filters-section').show();
						$('#purchase-history-filters-section').css('display', 'inline-flex');
						$("#aw-purchase-history-table").show();
						$('#page-loading').hide();
						//ordersVue.orders = response.data;
						historyDataTable = $("#aw-purchase-history-table").DataTable({
								data: response.data,
								columns: [
									{ data: 'order_type' },
									{ data: 'order_date' },
									{ data: 'order_link' },
									{ data: 'order_total' },
									{ data: 'payment_status' },
									{ data: 'ship_status' },
									{ data: 'tracking_link' },
									{ data: 'order_details' }
								],
								responsive: true,
								order: [[1, 'desc']],
								paging: true,
								pageLength: 10,
								bLengthChange: true,
								bFilter: true,
						});
						historyDataTable.draw();
						//getYearColumnData();
						minDate = findMin();
						minDate = findMax();

						setDownloadButtons();
						historyDataTable.on('page.dt', ()=> {
							//console.log('page update');
							var info = historyDataTable.page.info();
							//console.log('info', info);
							$('button.aw-invoice-download-button').click(e => {
								//console.log(' clicked download button ' );
								let paddleID = $(e.target).attr('data-paddleID');
								//console.log('paddleID ' ,  paddleID);
								let parent = $(e.target).parent()[0];
								let child = $(parent).children()[1];
								$(child).addClass('show');
								invoiceDownloadCall(paddleID, child);
							});
						})

					}
					else {
						//if there are no purchases
						$('#page-loading').hide();
						let noPurchases = document.createElement('p');
						$(noPurchases).html('There are no purchases in your purchase history.');
						$('#purchase-history-vue-root')[0].appendChild(noPurchases);
					}
      	}
      });
    }
    purchaseHistoryAjaxCall();

		const filterTypeSelect = (col, value) => {
			let filteredTable = historyDataTable
					.column(col)
					.search(value)
					.draw();
		}

		$.fn.dataTable.ext.search.push(
			function(settings, data, dataIndex){
				let min = $(datesInputFrom).val();
				min = new Date(min);
				min = min.getTime();
				let max = $(datesInputTo).val();
				max = new Date(max);
				max = max.getTime();
				let date = new Date(data[1]);
				date = date.getTime();
				if ( ( isNaN( min ) && isNaN( max ) ) ||
						 ( isNaN( min ) && date <= max ) ||
						 ( min <= date   && isNaN( max ) ) ||
						 ( min <= date   && date <= max ) )
				{
						return true;
				}
				return false;
			}
		);

		const filterDatesToFrom = (min, max) => {
			if(min === undefined){
				min = findMin();
			}
			if(max === undefined){
				max = findMax();
			}
			historyDataTable.draw();
		}

		$(datesInputFrom).on('change', (e) => {
			let value = $(e.target).val();
			value = new Date(value);
			value = value.getTime();
			minDate = value;
			filterDatesToFrom(value, '');
		});
		$(datesInputTo).on('change', (e) => {
			let value = $(e.target).val();
			value = new Date(value);
			value = value.getTime();
			maxDate = value;
			filterDatesToFrom('', value);
		});

		$(typeSelect).on('change', (e) => {
			let value = $(e.target).find('option:selected').text();
			$(typeInput).text(value);
			if(value === 'All'){
				value = '';
			}
			filterTypeSelect(0, value);
		});
		$(yearSelect).on('change', (e) => {
			let value = $(e.target).find('option:selected').text();
			$(yearInput).text(value);
			if(value === 'All'){
				value = '';
			}
			filterTypeSelect(1, value);
		});



		pageLoading.show();
	} // end if body has class
});
