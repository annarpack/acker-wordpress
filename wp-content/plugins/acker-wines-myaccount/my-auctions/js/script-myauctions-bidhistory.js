type="text/javascript";
jQuery(document).ready(function($) {
	console.log('myauctions script connected');
	const monthArr = ['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'November', 'December'];
	let bidHistoryVue = $('#aw-account-myauctions-bid-history-vue-root');
	const pageLoading = $('#page-loading');
	const appraisalSection = $('#aw-account-bidhistory-appraisal');
	const appraisalBuilder = $('#aw-account-bidhistory-appraisal-builder');
	const reviewButton = $(appraisalBuilder).find('button.aw-review-button');
	let appraisalNum = 0;
	const addToAppraisalButton = $('button.aw-appraisal');
	const appraisalTableSection = $(appraisalSection).find('.aw-appraisal-table');
	const appraisalTable = $(appraisalTableSection).find('#aw-bidhistory-appraisal-table');
	const addManualEntry = $(appraisalTableSection).find('.aw-addto-appraisal-button');
	const addToAppraisalTable = $(appraisalTableSection).find('#aw-addto-appraisal-table');
	const addToAppraisalTableSubmit = $(appraisalTableSection).find('.aw-addto-appraisal-table-submit');
	const submitAppraisalButton = $('#aw-bidhistory-appraisal-submit-button');

	const yearSelect = $('#aw-myauctions-bidhistory-year-select');
	const yearOptions = $('.aw-bidhistory-year-option');
	const auctionSelect = $('#aw-myauctions-bidhistory-auction-select');
	const auctionOptions = $('.aw-bidhistory-auction-option');
	const resultSelect = $('#aw-myauctions-bidhistory-result-select');
	const resultOptions = $('.aw-bidhistory-result-option');
	const searchInput = $('#bidhistory-filter-search');
	const searchFilterInfo = $('#aw-search-filter-info');

	const filterInputFrom = $('#bidhistory-filter-dates-from');
	const filterInputTo = $('#bidhistory-filter-dates-to');
	const hiddenFromDate = $('#bidhistory-fromDate-value');
	const hiddenToDate = $('#bidhistory-toDate-value');
	const bidhistoryForm = $('#bidhistory-date-form');
	const bidHistoryFilters = $('.aw-bidhistory-filters');
	const bidHistoryVueRoot = $('#aw-account-myauctions-bid-history-vue-root');

	let bidHistoryTable = new $.fn.dataTable.Api('#aw-account-myauctions-bid-history-table');
	let tables = $('#aw-account-myauctions-bid-history-vue-root').find('table.aw-account-auctions-table');
	let bidHistoryTables = new $.fn.dataTable.Api(tables);
	let appraisalTableDataTable = new $.fn.dataTable.Api('#aw-bidhistory-appraisal-table');
	const appraisalTableDiv = $('#aw-bidhistory-appraisal-table');
	appraisalTableDataTable = $('#aw-bidhistory-appraisal-table').DataTable({
		columns: [
			{ data: 'lotId' },
			{ data: 'quantity' },
			{ data: 'format' },
			{ data: 'vintage' },
			{ data: 'wineName' },
			{ data: 'designation' },
			{ data: 'producer' },
			{ data: 'bidAmount' },
			{ data: 'button' }
		],
		language: {
			emptyTable: "No wines selected"
		},
		responsive: true,
		order: [[0, 'asc']],
		paging: true,
		pageLength: 10,
		bLengthChange: true,
		bFilter: false
	});

	const getDateFormat = (input) => {
		//console.log('input', input);
		let date = new Date(input);
		//console.log('date', date);
		let y = date.getFullYear();
		let month = date.getMonth();
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
	const auctionInitNum = null;


	$(filterInputFrom).datepicker({
		dateFormat: "M d, yy",
		showButtonPanel: true,
		buttonImage: "/images/datepicker.gif"
	});
	$(filterInputTo).datepicker({
		dateFormat: "M d, yy",
		showButtonPanel: true,
		buttonImage: "/images/datepicker.gif"
	});

	let appraisalTableWrapper = $('#aw-bidhistory-appraisal-table_wrapper');
	appraisalTable.hide();
	$(appraisalTableDataTable).hide();
	addToAppraisalTable.hide();
	addToAppraisalTableSubmit.hide();
	appraisalTableWrapper.hide();
	submitAppraisalButton.hide();
	appraisalBuilder.hide();
	bidHistoryFilters.hide();

	let review = 'closed';
	reviewButton.html('Show Review');
	$(reviewButton).click((e) => {
		appraisalSection.toggle();
		addToAppraisalTable.toggle();
		addToAppraisalTableSubmit.toggle();
		appraisalTable.toggle();
		$(appraisalTableDataTable).toggle();
		appraisalTableWrapper.toggle();
		submitAppraisalButton.toggle();
		$('div.aw-button-container').toggle();

		if(review === 'closed'){
			reviewButton.html('Close Review');
			review = 'open';
		}
		else {
			reviewButton.html('Show Review');
			review = 'closed';
		}
	});


	// const addLoading = (parent) => {
	// 		// add loading information box
	// 		if( $('#page-loading').css('display') !== 'block' ){
	// 				if( $('#page-loading').css('display') === 'none' ){
	// 						$('#page-loading').css('display', 'block');
	// 						$('#page-loading').show();
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

		const beforeSendCall = () => {
			//let parent = $('#page-loading');
			//addLoading(parent);
			//$('#page-loading').show();
			pageLoading.show();
			$(bidHistoryVue).hide();
		}

		const getResultColumnData = () => {
			let auctions = [];
			let filteredTable = bidHistoryTables
					.column(8)
					.data()
					.filter( (value, index) => {
						auctions[value] = 1;
					});
			let resultKeys = Object.keys(auctions).sort().reverse();
			if(resultKeys.length > 0){
				$(resultKeys).each( (i, val) => {
					let auctionOption = document.createElement('option');
					$(auctionOption).text(val);
					$(resultSelect)[0].appendChild(auctionOption);

				})
			} // end if
		}


		const searchAllAuctions = (term) => {
			let filteredTables = bidHistoryTables.search(term).draw();
		}

		const updateAppraisalNumber = (num) => {
			const selected = $(appraisalSection).find('.aw-selected');
			$(selected).html(` ${num} Selected`);
		}

		const removeButtons = () => {
			$('button.remove-from-appraisal').click(e => {
				//get value type - either checkbox or manual add
				let removeButton = e.target;
				if($(e.target).hasClass('icon-close')){
					let parent = $(e.target).parents()[0];
					removeButton = parent;
				}
				let type = $(removeButton).attr('type');
				if(type === 'checkbox'){
					let lotID = $(removeButton).attr('lot-id');
					let productName = $(removeButton).attr('wine-name');
					let auction = $(removeButton).attr('auction');
					let vintage = $(removeButton).attr('wine-vin');
					let rowID = $(removeButton).attr('row-id');
					let tableID = `#aw-bidhistory-table-${auction}`;
					let item = $(tableID).find(`input:checked[type="checkbox"][row-id="${rowID}"][lot-id="${lotID}"][auction="${auction}"]`);
					if(item !== undefined){
						//uncheck checkbox on tables
						let checked = $(item).is(':checked');
						$(item).click();
						$(item).checked = false;
					}
				}
				else {
					let row = $(removeButton).parents('tr');
					appraisalTableDataTable.row(row).remove();
					appraisalTableDataTable.draw();
					appraisalNum--;
					updateAppraisalNumber(appraisalNum);
				}
			});
		}

		const chartButtons = () => {
			//console.log('data' , data );
			let checkboxes = $('body').find('input.aw-appraisal');
			$('body').on('click', '.aw-appraisal', function(e) {
				let checked = $(e.target).is(':checked');
				//console.log('checked', checked);
				//console.log($(this).is(':checked'));
				let executed = false;
				if(checked && executed === false){
					//console.log('checked');
					let auction = $(e.target).attr('auction');
					let lotID = $(e.target).attr('lot-id');
					let qty = $(e.target).attr('lot-qty');
					let name = $(e.target).attr('wine-name');
					let fmt = $(e.target).attr('wine-fmt');
					let vint = $(e.target).attr('wine-vin');
					let des = $(e.target).attr('wine-des');
					let prod = $(e.target).attr('wine-prod');
					let bidAmt = $(e.target).attr('bid-amt');
					let result = $(e.target).attr('result');
					let rowID = $(e.target).attr('row-id');
					let type = 'checkbox';
					let button = `<button class="remove-from-appraisal"
							auction="${auction}"
							lot-id="${lotID}"
							wine-name="${name}"
							wine-vin="${vint}"
							type="${type}"
							row-id="${rowID}"
						><i class="icon-close" type="${type}"></i></button>`;

					if(des == undefined || des == 'undefined' ){ des = ''; }
					if(prod == undefined || prod == 'undefined' ){ prod = ''; }
					let lotID_display = 'No: ' + auction + ' Lot: ' + lotID;
					let productObj = {
						lotId:  lotID_display,
						quantity: qty,
						format: fmt,
						vintage: vint,
						wineName: name,
						designation: des ? des : '',
						producer: prod ? prod : '',
						bidAmount: '$ ' + bidAmt,
						button: button
					}
					let size = Object.keys(productObj);
					size = size.length;
					if(size > 0){
						appraisalTableDataTable.row.add(productObj);
						appraisalTableDataTable.draw();
						let rows = $('#aw-bidhistory-appraisal-table').find('tbody').find('tr');
						removeButtons();
						appraisalNum++;
						updateAppraisalNumber(appraisalNum);
						executed = true;
					}
				}
				else {
					let lotID = $(e.target).attr('lot-id');
					let auction = $(e.target).attr('auction');
					let productName = $(e.target).attr('wine-name');
					let vintage = $(e.target).attr('wine-vin');
					let rowID = $(e.target).attr('row-id');
					let dataRow = $('#aw-bidhistory-appraisal-table').find(`button.remove-from-appraisal[row-id="${rowID}"][lot-id="${lotID}"][wine-name="${productName}"][wine-vin="${vintage}"]`);
					if(dataRow !== undefined){
						let row = $(dataRow).parents('tr');
						if(row !== undefined){
							appraisalTableDataTable.row(row).remove();
							appraisalTableDataTable.draw();
							appraisalNum--;
							updateAppraisalNumber(appraisalNum);
							executed = true;
						}
					}
				}
			});

			$(addToAppraisalTableSubmit).click(e => {
				let button = '<button class="remove-from-appraisal"><i class="icon-close"></i></button>';
				let inputs = $(addToAppraisalTable).find('input');
				let value = [];
				//console.log('inputs', inputs);
				$(inputs).each((i, elm) => {
						let v = $(elm).val();
						if(v !== ''){
							value.push(v);
						}
				});
				//console.log('value', value)
				if(value.length !== 5){
					let wineObj = {
						lotId: 'Manual Entry',
						quantity: value[0] ? value[0] : '',
						format: value[1] ? value[1] : '',
						vintage: value[2] ? value[2] : '',
						wineName: value[3] ? value[3] : '',
						designation: value[4] ? value[4] : '',
						producer: value[5] ? value[5] : '',
						bidAmount: null,
						button: button
					}
					appraisalTableDataTable.row.add(wineObj);
					appraisalTableDataTable.draw();
					let rows = $(appraisalTableDataTable).find('tbody').find('tr');
					//console.log(rows);
					let last = rows.length - 1;
					let tr = rows[last];

					let removeWine = $('.remove-from-appraisal');
					removeButtons(removeWine);

					appraisalNum++;
					//console.log('appraisal number', appraisalNum);
					updateAppraisalNumber(appraisalNum);
				}
			});
		}//end chart buttons

	    function formatAuctionDates(){
	        $('span.aw-saledate').each((i, elm) => {
	            let date = $(elm).text();
	            $(elm).text(date);
	        });
	    }

	    Vue.filter('longDate', function(auction, value) {
				let lotKeys = Object.keys(auction.lots);
				let lotNum = lotKeys[0];
				let auctionObj = auction.lots[lotNum];
				let date = auctionObj.saleDate.date;
				let noSuffix = auctionObj.auctionNoSuffix;
	      let newDate = new Date(date);
	      let y = newDate.getFullYear();
	      let m = newDate.getMonth();
	      let d = newDate.getDate();
	      let month = monthArr[m];
				let fullDate = `Auction ${noSuffix} - ${month} ${d}, ${y}`;
	      return fullDate
	    });

			Vue.filter('auctionTableID', (auction) => {
				let lotKeys = Object.keys(auction.lots);
				let lotNum = lotKeys[0];
				let auctionObj = auction.lots[lotNum];
				let tableID = `aw-bidhistory-table-${auctionObj.auctionNoSuffix}`;
				return tableID;
			});

			let bidHistoryApp = new Vue({
				data: {
					years: {},
				}
			});

			const yearButtonClickAction = (e) => {
				e.preventDefault();
				let parent = $(e.currentTarget).parent();
				let year = $(e.currentTarget).attr('id');
				let arrow = $(e.currentTarget).find('i')[0];
				$(arrow).toggleClass('fa-chevron-down');
				$(arrow).toggleClass('fa-chevron-right');
				let auctions = $(parent).find('div.aw-auction');
				$(auctions).toggle();
			}

			const yearButtonClick = (buttons) => {
				$(buttons).unbind("click");
				if(buttons.length > 1){
					$(buttons).each((i, elm) => {
						$(elm).click(_.debounce((e) => {
							yearButtonClickAction(e);
						}, 500));
					});
				}
				else {
					$(buttons).click(_.debounce((e) => {
						yearButtonClickAction(e);
					}, 500));
				}

			}

		const configDataTables = (data) => {
			console.log('data', data);
			let tables = $('#aw-account-myauctions-bid-history-vue-root').find('table.aw-account-auctions-table');
			const host = window.location.origin;
			let url = ''; let link = ''; let button = '';
			tables.each((i, elm) => {
				if(elm !== undefined){
					let tbl = $(elm).dataTable({
						columnDefs: [
							{ targets: 0,
								data: 'lotId' },
							{ targets: 1,
								data: 'quantity' },
							{ targets: 2,
								data: 'format' },
							{ targets: 3,
								data: 'vintage' },
							{ targets: 4,
								data: 'wineName' },
							{ targets: 5,
								data: 'appellation' },
							{ targets: 6,
								data: 'producer' },
							{ targets: 7,
								data: 'bidAmount' },
							{ targets: 8,
								data: 'result'},
							{ targets: 9,
								data: function(row, type, full, meta){
									var name = encodeURI(row.wineName);
									var	url = `${host}/?s=${name}`;
									var	link = `<a href="${url}" class="aw-button aw-red-button">Search</a>`;
										return link;
									}
							},
							{ targets: 10,
								data: 'auctionNoSuffix' },
							{ targets: 10,
								data: function(row, type, full, meta){
									var auction = row.auctionNoSuffix;
									console.log('auctionno', auction)
									$(elm).attr('id', auction);
									return $(elm).attr('id', auction);
								}
							}
						],
						// rowCallback: function(row, data, index){
						// 	//console.log(row);
						// 	var wineID = `${data.auctionId}-${data.lot}-${data.vintage}-${data.wineName}`;
						// 	console.log(wineID);
						// 	var	checkbox = `<input class="aw-appraisal"
						// 			type=checkbox
						// 			auction="${data.auctionId}"
						// 			lot-id="${data.lotId}"
						// 			lot-qty="${data.quantity}"
						// 			wine-fmt="${data.format}"
						// 			wine-vin="${data.vintage}"
						// 			wine-name="${data.wineName}"
						// 			wine-des="${data.designation}"
						// 			wine-prod="${data.producer}"
						// 			bid-amt="${data.bidAmount}"
						// 			result="${data.result}"
						// 			data-row="${index}" />`;
						// 	if(data.result === '<b>Win</b>'){
						// 		$('td:eq(10)', row).html(checkbox);
						// 	}
						// 	else {
						// 		$('td:eq(10)', row).html('');
						// 	}
						// },
						responsive: true,
						order: [[0, 'asc']],
						paging: true,
						pageLength: 10,
						bLengthChange: true,
						bFilter: true,
						searching: true,
						destroy: true
					});
				}
			});
			return tables;
		}



		const getBidHistoryCall = (fromDate, toDate) => {
			//console.log('fromDate', fromDate);
			//console.log('toDate', toDate);
			// acker.api.getProgBids(5365, null, null, (response, success, xhr)=>{
			// 	console.log('response', response);
			// });
			beforeSendCall();
			acker.api.getProgBids(null, fromDate, toDate, (response, success, xhr)=>{
				console.log('response', response);
				//console.log('response.data', response.data);
				if(success){
					if(response.data === undefined || response.data.length === 0){
						pageLoading.hide();
						appraisalBuilder.show();
						bidHistoryFilters.show();
						$('#aw-account-myauctions-bid-history-vue-root').html('<h3>There are no bids in your bid history.</h3>').show();
						//chartButtons();
						//createAppraisalTable();
					}
					else if(response.data.length !== 0 && response.data[0] !== null ){
	          bidHistoryApp.years = response.data.years;
	          bidHistoryApp.$mount('#aw-account-myauctions-bid-history-vue-root');
	          bidHistoryApp.$forceUpdate();

						//let tables = $('#aw-account-myauctions-bid-history-vue-root').find('table.aw-account-auctions-table');
						//let bidHistoryTables = new $.fn.dataTable.Api(tables);
						let bidHistoryTables = configDataTables(response.data);

						if($.fn.dataTable.isDataTable( bidHistoryTables )){
							let dataTableFilters = $('.dataTables_filter').find('input');
							$(searchInput).on('keyup', (e) => {
								let term = $(e.target).val();
								$(dataTableFilters).each((i, elm) => {
									$(elm).val(term);
								});
								$(bidHistoryTables).DataTable().search(term).draw();
								//chartButtons();
							});
							$(resultSelect).on('change', (e) => {
								let value = $(e.target).val();
								if(value === 'All Results'){
									$(bidHistoryTables).DataTable().search('').draw();
								}
								else {
									$(bidHistoryTables).DataTable().search(value).draw();
								}
								//chartButtons();
							});
						}

						let yearButtons = $('.aw-year-buttons');
						yearButtonClick(yearButtons);
						bidHistoryFilters.show();
						appraisalBuilder.show();
						$('#aw-account-myauctions-bid-history-vue-root').show();
						pageLoading.hide();
						$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
						chartButtons();

	          //formatAuctionDates();
						// let checkboxes = $('input.aw-appraisal');
						// chartButtons(checkboxes);

						// $(bidHistoryTables).on( 'draw.dt', function () {
						// 	console.log('Table Redrawn!');
						//
						// });
						// $(bidHistoryTables).on( 'order.dt', function () {
						// 	console.log('Table Reordered!');
						// 	chartButtons();
						// });
						// $(bidHistoryTables).on( 'search.dt', function () {
						// 	console.log('Table Search!');
						// 	//let checkboxes = $('input.aw-appraisal');
						// 	//chartButtons(checkboxes);
						// });
						// $(bidHistoryTables).on( 'page.dt', function () {
						// 	console.log('Table Redrawn!');
						// 	//let checkboxes = $('input.aw-appraisal');
						// 	//chartButtons(checkboxes);
						// });
					}
				} // end if
				else {
					//chartButtons();
					//createAppraisalTable();
				}
			}); // end ajax
		}


		// const updateFilterInfo = (from, to) => {
		// 	let fromDate = getDateFormat(from);
		// 	let toDate = getDateFormat(to);
		// 	const initURL = `http://${host}/my-account/bid-history?fromDate=${fromDate}&toDate=${toDate}`;
		// 	console.log('initurl', initURL);
		// 	this.location.assign(initURL);
		// }

		const updateFilterInfo = (from, to) => {
			let fromDate = new Date(from);
			let toDate = new Date(to);
			// $(hiddenFromDate).val(fromDate);
			// $(hiddenToDate).val(toDate);
			$(filterInputFrom).datepicker("setDate", fromDate);
			$(filterInputTo).datepicker("setDate", toDate);
			// let fromVal = $(filterInputFrom).val();
			// let toVal = $(filterInputTo).val();
			$(searchFilterInfo).html(`Searching dates from ${from} to ${to}`);
			//updateBidHistoryCall(auctionInitNum, fromDate, toDate);
		}

		let fromValHidden = $(hiddenFromDate).val();
		let toValHidden = $(hiddenToDate).val();
		// console.log('fromValHidden', fromValHidden);
		// console.log('toValHidden', toValHidden);
		if(fromValHidden !== undefined && fromValHidden !== null && fromValHidden !== ''){
			if(toValHidden !== undefined && toValHidden !== null && toValHidden !== ''){
				//if dates are set
				//console.log('dates are set');
				let fromDate = new Date(fromValHidden);
				let toDate = new Date(toValHidden);
				if(fromValHidden == fromDate){
					fromValHidden = getDateFormat(fromValHidden);
				}
				if(toValHidden == toDate){
					toValHidden = getDateFormat(toValHidden);
				}
				updateFilterInfo(fromValHidden, toValHidden);
				getBidHistoryCall(fromValHidden, toValHidden);
			}
		}
		else {
			//if neither date is set
			//console.log('neither date is set');
			let maxDate = new Date();
			let fullYear = maxDate.getFullYear();
			let month = maxDate.getMonth();
			if(month < 4){
				fullYear = fullYear - 1;
			}
			let minDate = new Date(fullYear, 0, 1);
			$(filterInputTo).datepicker("setDate", maxDate);
			$(filterInputFrom).datepicker("setDate", minDate);
			$(hiddenFromDate).val(minDate);
			$(hiddenToDate).val(maxDate);
			let datesInputTo = getDateFormat(maxDate);
			let datesInputFrom = getDateFormat(minDate);
			$(searchFilterInfo).html(`Searching dates from ${datesInputFrom} to ${datesInputTo}`);
			getBidHistoryCall(datesInputFrom, datesInputTo);
		}

		// $(hiddenFromDate).on('change', (e) => {
		// 	let value = $(e.target).val();
		// 	console.log('value from', value);
		// 	//updateFilterInfo(value, '');
		// 	//value = getDateFormat(value);
		// 	//$(filterInputFrom).val(value);
		// });
		// $(hiddenToDate).on('change', (e) => {
		// 	let value = $(e.target).val();
		// 	console.log('value to', value);
		// 	//updateFilterInfo(value, '');
		// 	//value = getDateFormat(value);
		// 	//$(filterInputTo).val(value);
		// });

		$(filterInputFrom).on('change', (e) => {
			let value = $(e.target).val();
			//console.log('value', value);
			value = getDateFormat(value);
			$(hiddenFromDate).val(value);
		});
		$(filterInputTo).on('change', (e) => {
			let value = $(e.target).val();
			//console.log('value', value);
			value = getDateFormat(value);
			$(hiddenToDate).val(value);
		});

		$('#aw-date-search').click( (e) => {
			let from = $(filterInputFrom).val();
			let to = $(filterInputTo).val();
			let fromDate = getDateFormat(from);
			let toDate = getDateFormat(to);
			//console.log('fromdate', fromDate);
			//console.log('todate', toDate);
			//updateFilterInfo(from, to);
		});

		const beforeSendAppaisal = () => {
			let loading = document.createElement('div');
			$(loading).attr('id', 'appraisalLoading');
			$(loading).html('<svg viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><circle cx="50" cy="50" fill="none" ng-attr-stroke="{{config.color}}" ng-attr-stroke-width="{{config.width}}" ng-attr-r="{{config.radius}}" ng-attr-stroke-dasharray="{{config.dasharray}}" stroke="#7a7a7a" stroke-width="12" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">	<animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform></circle></svg>');
			$('.aw-button-container')[0].appendChild(loading);
		}

		//need to finish appraisal form action
		$(submitAppraisalButton).click(e => {
			let data = appraisalTableDataTable.data();
			let newData = [];
			$(data).each((i, elm) => {
				newData.push(elm);
			});
			acker.api.saveAppraisalTable(beforeSendAppaisal, newData, 'bid-history', (response, success, xhr) => {
				console.log(' response' , response );
				if(success){
					console.log('success', success);
					$('#appraisalLoading').html('Appraisal Saved!');
				}
			});
		});

		pageLoading.show();
});
