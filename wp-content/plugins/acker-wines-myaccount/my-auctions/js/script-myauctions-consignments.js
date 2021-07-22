type="text/javascript";
jQuery(document).ready(function($) {
	console.log('myauctions script connected');
	const auctionInitNum = null;
	const toVal =  new Date();
	const fromVal = new Date(toVal.getFullYear(), 0, 1);

	const getDateFormat = (date) => {
		let y = date.getFullYear();
		let m = date.getMonth() + 1;
		m = m.toString();
		if(m.length < 2){
			m = '0' + m;
		}
		let d = date.getDay();
		d = d.toString();
		if(d.length < 2){
			d = '0' + d;
		}
		let newDate = `${y}-${m}-${d}`;
		return newDate
	}

	const datesInputFrom = getDateFormat(fromVal);
	const datesInputTo = getDateFormat(toVal);

	$('#bidhistory-filter-dates-from').datepicker({
		dateFormat: "M d, yy",
		showButtonPanel: true,
		buttonImage: "/images/datepicker.gif"
	});
	$('#bidhistory-filter-dates-to').datepicker({
		dateFormat: "M d, yy",
		showButtonPanel: true,
		buttonImage: "/images/datepicker.gif"
	});
	$('#bidhistory-filter-dates-from').datepicker("setDate", fromVal);
	$('#bidhistory-filter-dates-to').datepicker("setDate", toVal);

const getProgConsignorSalesInit = (auction, fromDate, toDate) => {
	//beforeSendCall();
	acker.api.getProgConsignorSales(auctionInitNum, fromDate, (response, success, xhr)=>{
		console.log('response', response);
		console.log('response.data', response.data);

	});
}
 getProgConsignorSalesInit(auctionInitNum, fromVal);

});
