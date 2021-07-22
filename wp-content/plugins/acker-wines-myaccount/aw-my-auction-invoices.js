type="text/javascript";
jQuery(document).ready(function($) {
    console.log('connected');
    const makeAjaxCall = (filter) => {
        //console.log(filter);
        const url = 'http://localhost:3000/payments';

        $.ajax({
            type: 'post',
            url: url,
            data: {
                action: 'aw_my_auctions_get'
            },
            beforeSend: function() {
                //addLoading(parent);
            },
            success: function( res ) {
                console.log(res);

                $('#filtered-content').html(res);
            },
            error: function(jqxhr, status, errorThrown) {
                console.log(status);
                console.log('error!', errorThrown);
            }
            }).done( () => {
                //console.log('button clicked');
            })
            return false
    };

    makeAjaxCall();


});
