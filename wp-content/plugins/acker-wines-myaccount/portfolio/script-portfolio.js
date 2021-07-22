type="text/javascript";
jQuery(document).ready(function($) {

	if($('body').hasClass('woocommerce-my-products')){
    console.log('my portfolio script connected');

		const vueRootWines = $('#my-wines-vue-root');
		const winesBySection = $('#my-wines-by-section');
		const winesTable = $('#aw-my-wines-table');
		const pageLoading = $('#page-loading');
		const appraisalSection = $('#my-wines-appraisal-table');
		const appraisalTableDiv = $('#aw-appraisal-table');
		const appraisalLightbox = $('#add-to-appraisal-lightbox');
		const openLightbox = $('button#show-appraisal-lightbox-button');
		const closeLightbox = $('button#appraisal-lightbox-close');
		const addToAppraisalTable = $('#add-to-appraisal-table');
		const addToAppraisalButton = $('button#submit-addto-form');
		const submitAppraisalButton = $('button#submit-appraisal-form');
		const appraisalBuilder = $('#aw-myaccount-mywines-appraisal-builder');
		const clearFilters = $('button#clear-filters');
		let appraisalNum = 0;
		const filtersSection = $('#aw-mywines-filters-section');
		const typeFilterDiv = $(filtersSection).find('div.aw-type');
		const typeInput = $(filtersSection).find('input#aw-mywines-filter-selection-type');
		const typeSelect = $(filtersSection).find('select.type-filter');
		const typeOptions = $(filtersSection).find('option');


		let regionData = []; let vintageData = []; let producerData = []; let formatData = [];
		const chartTitlesArr = ['Region', 'Vintage', 'Producer', 'Format'];
		const dataTableColumnsArr = [7, 5, 9, 4];
		const wineChartDivsArr = ['#my-wines-chart-region', '#my-wines-chart-vintage', '#my-wines-chart-producer', '#my-wines-chart-format'];
		// let winesVue = new Vue({
		// 	el: "#my-wines-vue-root"
		// });
		Highcharts.setOptions({	lang: { thousandsSep: ',' }	});
		let wineChartsData = {};
		const chartDivs = ['region', 'vintage', 'producer', 'format'];
		const getChartIDs = (id) => {
			return `#my-wines-chart${id}`;
		}

		let winesDataTable  = new $.fn.dataTable.Api('#aw-my-wines-table');
		let appraisalEditor = new $.fn.dataTable.Api('#aw-appraisal-table');

		let appraisalTableDataTable = $('#aw-appraisal-table').DataTable({
			columns: [
				{ data: 'order_type' },
				{ data: 'order_date' },
				{ data: 'order_link' },
				{ data: 'wine_qty' },
				{ data: 'wine_format' },
				{ data: 'wine_vintage' },
				{ data: 'wine_link' },
				{ data: 'wine_region' },
				{ data: 'wine_designation' },
				{ data: 'wine_producer' },
				{ data: 'action_button' }
			],
			responsive: true,
			order: [[1, 'desc']],
			pageLength: 10,
			bLengthChange: true,
			bFilter: false
		});

		vueRootWines.hide();
		winesBySection.hide();
		appraisalSection.hide();


		// const addLoading = (parent) => {
    //     // add loading information box
    //     if( $('#loading').css('display') !== 'block' ){
    //         if( $('#loading').css('display') === 'none' ){
    //             $('#loading').css('display', 'block');
    //             $('#loading').show();
    //         } else {
    //             const loading = document.createElement('div');
    //             $(loading).attr('id', 'loading');
    //             const text = document.createElement('p');
    //             $(text).html('  Loading, please wait ...  ');
    //             $(loading)[0].appendChild(text);
    //             $(parent)[0].appendChild(loading);
    //         }
    //     }
    // }


		const beforeSendCall = () => {
			// let parent = $('#page-loading');
			// addLoading(parent);
			pageLoading.show();
			vueRootWines.hide();
			winesBySection.hide();
			appraisalSection.hide();
		}

		const removeButtons = () => {
			$('button.remove-from-appraisal').click(e => {
				//get value type - either checkbox or manual add
				//console.log(e.target);
				let removeButton = e.target;
				if($(e.target).hasClass('icon-close')){
					let parent = $(e.target).parents()[0];
					removeButton = parent;
				}
				//console.log(removeButton);
				let type = $(removeButton).attr('type');
				if(type === 'checkbox'){
					let lotID = $(removeButton).attr('lot-id');
					let productDate = $(removeButton).attr('order-date');
					let productName = $(removeButton).attr('wine-name');
					let auction = $(removeButton).attr('auction');
					let vintage = $(removeButton).attr('wine-vin');
					let tableID = `#aw-bidhistory-table-${auction}`;
					let item = $('#aw-my-wines-table').find(`input[type="checkbox"][order-date="${productDate}"][wine-name="${productName}"][wine-vin="${vintage}"]`);
					//console.log('item', item)
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

		const updateAppraisalNumber = (num) => {
			const selected = $('#aw-account-mywines-appraisal-builder').find('.aw-selected');
			$(selected).html(` ${num} Selected`);
		}

		const chartButtons = (data) => {
			//console.log('clicked checkbox');
				$('body').on('click', '.aw-appraisal', function(e) {
					//console.log(e.target);
					let checked = $(e.target).is(':checked');
					// console.log('checked', checked);
					// console.log($(this).is(':checked'));
					if(checked){
						let order_type = $(e.target).attr('order-type');
						let order_date = $(e.target).attr('order-date');
						let order_num = $(e.target).attr('order-num');
						//console.log('order_num', order_num);
						// order_num.slice(0, 1);
						// order_num.pop();
						// console.log('order_num', order_num);
						let qty = $(e.target).attr('wine-qty');
						let name = $(e.target).attr('wine-name');
						let link = $(e.target).attr('wine-link');
						let fmt = $(e.target).attr('wine-fmt');
						let vint = $(e.target).attr('wine-vin');
						let region = $(e.target).attr('wine-region');
						let des = $(e.target).attr('wine-des');
						let prod = $(e.target).attr('wine-prod');
						let row = $(e.target).attr('data-row');
						let type = 'checkbox';
						let button = `<button class="remove-from-appraisal"
									order-date="${order_date}"
									wine-name="${name}"
									wine-vin="${vint}"
									data-row="${row}"
									type="${type}"
								><i class="icon-close"></i></button>`;
						let productObj = {
							order_type: order_type,
							order_date: order_date,
							order_link: order_num,
							wine_qty: qty,
							wine_format: fmt,
							wine_vintage: vint,
							wine_link: name,
							wine_region: region ? region : '',
							wine_designation: des ? des : '',
							wine_producer: prod ? prod : '',
							action_button: button
						}
						//console.log(productObj);
						let size = Object.keys(productObj);
						size = size.length;
						//console.log('size', size);
						if(size > 0){
							appraisalTableDataTable.row.add(productObj);
							appraisalTableDataTable.draw();
							let rows = $(appraisalTableDiv).find('tbody').find('tr');
							let last = rows.length - 1;
							let tr = rows[last];
							let removeWine = $('.remove-from-appraisal');
							removeButtons(removeWine);
							appraisalNum++;
							updateAppraisalNumber(appraisalNum);
						}
						//} //end if rowNum
					} // if checked
					else {
						let productDate = $(e.target).attr('order-date');
						let productName = $(e.target).attr('wine-name');
						let vintage = $(e.target).attr('wine-vin');
						//let row = $(e.target).attr('data-row');
						let dataRow = $(appraisalTableDiv).find('tbody').find(`button.remove-from-appraisal[order-date="${productDate}"][wine-name="${productName}"][wine-vin="${vintage}"]`);
						if(dataRow !== undefined){
							let row = $(dataRow).parents('tr');
							if(row !== undefined){
								appraisalTableDataTable.row(row).remove();
								appraisalTableDataTable.draw();
								appraisalNum--;
								updateAppraisalNumber(appraisalNum);
							}
						}
					}
				});
			//}

		}

		const setupDataTable = (data) => {
			winesDataTable = $('#aw-my-wines-table').DataTable({
				data: data,
				columns: [
					{ data: 'order_type' },
					{ data: 'order_date' },
					{ data: 'order_link' },
					{ data: 'wine_qty' },
					{ data: 'wine_format' },
					{ data: 'wine_vintage' },
					{ data: 'wine_link' },
					{ data: 'wine_region' },
					{ data: 'wine_designation' },
					{ data: 'wine_producer' },
					{ data: function(row, type, full, meta){
						var checkbox = `<input class="aw-appraisal"
								type="checkbox"
								order-num="'${row.order_number}'"
								order-type="${row.order_type}"
								order-date="${row.order_date}"
								wine-qty="${row.wine_qty}"
								wine-fmt="${row.wine_format}"
								wine-vin="${row.wine_vintage}"
								wine-name="${row.wine_name}"
								wine-region="${row.wine_region}"
								wine-des="${row.wine_designation}"
								wine-prod="${row.wine_producer}"
							 />`;
						return checkbox;
					} }
				],
				responsive: true,
				scrollY: '612px',
				order: [[1, 'desc']],
				paging: false,
				bLengthChange: true,
				bFilter: true,
			});
			winesDataTable.draw();

			$(winesDataTable).on( 'draw.dt', function () {
				console.log('Table Redrawn!');
				chartButtons(response.data);
			});

			const filterTypeSelect = (col, value) => {
				let filteredTable = winesDataTable
						.column(col)
						.search(value)
						.draw();
			}
			$(typeSelect).on('change', (e) => {
				let value = $(e.target).find('option:selected').text();
				$(typeInput).text(value);
				if(value === 'All'){
					value = '';
				}
				filterTypeSelect(0, value);
			});


		}

		const myWinesAjaxCall = () => {
			// acker.api.getProgInvoiceHeaders((response, success, xhr) => {
			// 	console.log('getProgInvoiceHeaders response' , response );
			// });
			// acker.api.getProgInvoiceDetails(137567, (response, success, xhr) => {
			// 	console.log('getProgInvoiceDetails response' , response );
			// });
			acker.api.getPortfolioWines(beforeSendCall, (response, success, xhr) => {
				console.log('getPortfolioWines response 1', response);
				if(success){
					$('#page-loading').hide();
					vueRootWines.show();
					if(response.data === undefined || response.data === null || response.data.length === 0){
						//if there are no purchases
						$('#page-loading').hide();
						vueRootWines.html('<h3>There are no wines in your portfolio.</h3>');
						winesBySection.hide();
						appraisalTableDiv.hide();
					}
					else if(response.data.length > 1){
						//console.log('response data ', response.data);
						wineChartsData = response.data;
						$('#my-wines-charts').show();
						appraisalBuilder.show();
						winesBySection.show();
						appraisalSection.show();
						appraisalTableDiv.show();
						$(appraisalTableDataTable).show();
						filtersSection.show();
						getChartData(wineChartsData);
						setupDataTable(wineChartsData);
						chartButtons(wineChartsData);
					}
					else {
						//if there are no purchases
						$('#page-loading').hide();
						vueRootWines.html('<h3>There are no wines in your portfolio.</h3>');
						winesBySection.hide();
						appraisalTableDiv.hide();
					}

				}
			}); // end ajax call
		} // end func
		myWinesAjaxCall();

		const tableFilter = (category, status, value, target) => {
			// console.log('status', status)
			// console.log('value', value)
			if(!$(target).hasClass('selected')){
				$(target).addClass('selected');
				for(let i = 0; i < dataTableColumnsArr.length; i++ ){
					let columnNum = dataTableColumnsArr[i];
					let title = chartTitlesArr[i];
					if(title === category){
						winesDataTable.columns(columnNum).search(status).draw();
					}
				}
			}
			else {
				$(target).removeClass('selected');
				for(let i = 0; i < dataTableColumnsArr.length; i++ ){
					let columnNum = dataTableColumnsArr[i];
					winesDataTable.columns(columnNum).search('').draw();
				}
			}
		}

		const legendFilter = (chartData, category, status, value, target ) => {
			// I have no fucking clue how to do this
			// console.log('status', status);
			// console.log('value', value);
			if(!$(target).hasClass('selected')){
				$(target).addClass('selected');
				for(let i = 0; i < dataTableColumnsArr.length; i++ ){
					let columnNum = dataTableColumnsArr[i];
					if(category === chartTitlesArr[i]){
						winesDataTable.columns(columnNum).search(status).draw();
					}
				}
			}
			else {
				$(target).removeClass('selected');
				for(let i = 0; i < dataTableColumnsArr.length; i++ ){
					let columnNum = dataTableColumnsArr[i];
					winesDataTable.columns(columnNum).search('').draw();
				}
			}
		}

		//==> DO TO: improve efficiency by only looping once rather than 4 four times!
		const formatPointData = (data, label, array) => {
			//console.log('label', label);
			let totalWineCount = 0;
			$(data).each((i, elm) => {
				//let name = elm[label] ? elm[label] : 'None' ;
				if(elm !== undefined){
					let name = elm[label];
					let qty = parseInt(elm.wine_qty);
					//console.log('qty', qty);
					totalWineCount += qty;
					if(array[name]){
						array[name] += qty;
					}
					else {
						array[name] = qty;
					}
				}
			});
			let keys = Object.keys(array);
			let values = Object.values(array);
			///console.log('values', values);
			let dataArr = [];
			for(let i=0; i < keys.length; i++){
				//console.log('totalWineCount', totalWineCount);
				let perc = (values[i] / totalWineCount) * 100;
				//console.log('perc', perc);
				dataArr.push({name: keys[i], y: perc});
			};
			return dataArr
		}


		const drawCharts = (chartDataArr) => {
			let totalWineCount = 0;
			for(let i = 0; i < 4; i++){
				let chartID = wineChartDivsArr[i];
				let chartTitle = chartTitlesArr[i];
				let chartData = chartDataArr[i];
				//console.log('chartData', chartData);
				let chart_height = 380;
				let legend_top = 40 + ((20- Math.min(20, $(chartData).length))*((chart_height-160)/40));
				$(chartID).highcharts({
					chart: {
						type: 'pie',
						height: chart_height + 'px',
						backgroundColor: '#F7F7F7',
						events: {
							load: function() {
								var data = this.series[0].data,
								newData = [];
								data.forEach(function(point) {
									newData.push({
										y: point.y,
										name: point.name
									})
								});
								newData.sort(function(a, b) {
									return b.y - a.y;
								});
								this.series[0].setData(newData);
							},
							redraw: function(e){
								//console.log(data)
								// console.log(chartData)
								// this.series[0].setData(chartData);
								// var data = this.series[0].data;
							}
						},
					},
					title: { text: chartTitle },
					subtitle: { text: '' },
					legend: {
						enabled: true,
						align: 'right',
						verticalAlign: 'top',
						layout: 'vertical',
						labelFormat:'{name} ({y:,.0f}%)',
						x: 0,
						y: legend_top,
						itemStyle: {
							width: 112,
							textOverflow: null
						},
						navigation: {
						 activeColor: '#3E576F',
						 animation: true,
						 arrowSize: 12,
						 inactiveColor: '#CCC',
						 style: {
								 fontWeight: 'bold',
								 color: '#333',
								 fontSize: '12px',
							}
						}
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							colors: ['#801747', '#182A7F', '#187D7F', '#87A519', '#DB9F35', '#DB662C', '#7F4E29'],
							showInLegend:true,
							dataLabels: {
								enabled:false,
							},
						},
						series: {
							data: chartData,
							point: {
								events: {
									click: function (e) {
										var data = chartData[this.x];
										tableFilter(chartTitle, data.name, this.y, e.target );
									},
									legendItemClick: function (e) {
										var data = chartData[this.x];
										legendFilter(chartData, chartTitle, data.name, this.y, e.target );
									},
									// hide: function(e){
									// 	console.log(e.target);
									// 	console.log('hide');
									// }
								}
							},
							dataSorting: {
								enabled: true,
								sortKey: 'y'
							},
							click: function(e){
								console.log(e);
							}

						}
					},
					yAxis: { reversed: true},
					tooltip: { pointFormat: '{point.percentage:.0f}%'},
					series: [{
						name: chartTitle,
						colorByPoint: true,
						type: 'pie',
						data: chartData,
					}],
					exporting: { enabled: false },
					credits: { enabled: false },
				});
			}
		}

		const getChartData = () => {
			//console.log('winedata', wineChartsData);
			formatData = formatPointData(wineChartsData, 'wine_format', formatData);
			regionData = formatPointData(wineChartsData, 'wine_region', regionData);
			producerData = formatPointData(wineChartsData, 'wine_producer', producerData);
			vintageData = formatPointData(wineChartsData, 'wine_vintage', vintageData);
			//console.log('formatData', formatData)
			let chartDataArr = [regionData, vintageData, producerData, formatData];
			drawCharts(chartDataArr);
		}

		$(clearFilters).click(e => {
			// console.log('wineChartsData', wineChartsData);
			$(typeSelect).val('All');
			for(let i = 0; i < dataTableColumnsArr.length; i++){
				let columnNum = dataTableColumnsArr[i];
				winesDataTable.columns(columnNum).search('').draw();
			}
			winesDataTable.search('').draw();
			//console.log('formatData', formatData)
			//console.log(regionData)
			let chartDataArr = [regionData, vintageData, producerData, formatData];
			for(let i = 0; i < wineChartDivsArr.length; i++){
				let div = wineChartDivsArr[i];
				let selected = $(div).find('path.selected')[0];
				if(selected){
					let points = $(div).highcharts().getSelectedPoints()[0];
					$(points).select();
				}
			}
		});

		openLightbox.click(e => {
			appraisalLightbox.show();
		});

		closeLightbox.click(e => {
			appraisalLightbox.hide();
		});

		if( appraisalLightbox.css('display') === 'block' ){
			$('body').click(e => {
				appraisalLightbox.hide();
			});
		}


		$(addToAppraisalButton).click(e => {
			console.log('clicked checkbox')
			e.preventDefault();
			let inputs = $(addToAppraisalTable).find('input');
			console.log('inputs', inputs);
			let qty = $(inputs[0]).val();
			qty = `x ${qty}`;
			let fmt = $(inputs[1]).val();
			let vintage = $(inputs[2]).val();
			let name = $(inputs[3]).val();

			let button = '<button id="remove-from-appraisal">X</button>';
			let productObj = {
				order_type: '',
				order_date: '',
				order_link: '',
				wine_qty_display: qty,
				wine_format: fmt,
				wine_vintage: vintage,
				wine_link: name,
				wine_region: '',
				wine_designation: '',
				wine_producer: '',
				action_button: button
			}
			appraisalTableDataTable.row.add(productObj);
			appraisalTableDataTable.draw();
			let removeWine = $('.remove-from-appraisal');
			removeButtons(removeWine);
			appraisalLightbox.hide();
		});

		const beforeSendAppaisal = () => {
			let loading = document.createElement('div');
			$(loading).attr('id', 'appraisalLoading');
			$(loading).html('<svg viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><circle cx="50" cy="50" fill="none" ng-attr-stroke="{{config.color}}" ng-attr-stroke-width="{{config.width}}" ng-attr-r="{{config.radius}}" ng-attr-stroke-dasharray="{{config.dasharray}}" stroke="#7a7a7a" stroke-width="12" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">	<animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform></circle></svg>');
			$('#my-wines-appraisal-table')[0].append(loading);
		}

		//need to finish appraisal form action
		submitAppraisalButton.click(e => {
			let data = appraisalTableDataTable.data();
			let newData = [];
			$(data).each((i, elm) => {
				newData.push(elm);
			});
			// console.log(newData);
			acker.api.saveAppraisalTable(beforeSendAppaisal, newData, 'my-wines', (response, success, xhr) => {
				console.log(' response' , response );
				if(success){
					console.log('success', success);
					$('#appraisalLoading').html('Appraisal Saved!');

				}
			});
		});

		pageLoading.show();
	} // if has class
});
