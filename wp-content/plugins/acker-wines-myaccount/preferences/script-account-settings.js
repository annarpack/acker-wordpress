jQuery(document).ready(function($) {
	if($('body').hasClass('woocommerce-account')){
		//console.log('my account settings script connected');

		let conditionInputs = $('#aw-conditions').find('input[type=checkbox]');
		let notificationInputs = $('#aw-account-notifications').find('input[type=checkbox]');
		const appraisalButton = $('#appraisal-submit-button');
		const appraisalForm = $('#appraisal-submit-form');


		$('input[type=checkbox]').click(e => {
			//console.log(e.target);
			$(e.target).val('1');
		});

		$(conditionInputs).click(e => {
			//console.log(e.target);
			$(e.target).val('1');
		});
		$(notificationInputs).click(e => {
			$(e.target).val('1');
		});

		$(appraisalForm).hide();

		$(appraisalButton).click(e => {
			$(appraisalForm).toggle();
		});
	}
});
