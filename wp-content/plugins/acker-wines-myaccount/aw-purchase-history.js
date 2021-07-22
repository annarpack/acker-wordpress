type="text/javascript";
jQuery(document).ready(function($) {
    console.log('connected');

    $('#loading').hide();
    const addLoading = (parent) => {
        // add loading information box
        if( $('#loading').css('display') !== 'block' ){
            if( $('#loading').css('display') === 'none' ){
                $('#loading').css('display', 'block');
                $('#loading').show();
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

    const makeAjaxCall = (filter) => {
        //console.log(filter);
        let parent = $('#filtered-content');
        $.ajax({
            type: 'post',
            url: myAjax.ajaxurl,
            data: {
                action: 'aw_purchase_history_filter_click',
                filter: filter
            },
            beforeSend: function() {
                const loading = $('.loading');
                addLoading(loading);
            },
            success: function( res ) {
                //console.log(res);
                $('#loading').hide();
                $(parent).html(res);
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

    const section = $('#purchase-history-filter-buttons-section.tabs');
    //const buttons = $(section).find('button');
    const tabs = $(section).find('.tab');
    const inputs = $(tabs).find('input.filter-buttons');

    const setTabs = () => {
        $(tabs).each( (i, elm) =>{
            const label = $(elm).children()[1];
            if(i === 0){ $(label).addClass('filter-selected'); }
           else{ $(label).addClass('filter-not-selected'); }
        })
    }
    setTabs();

    const resetFilterTabs = () => {
        $(tabs).each( (i, elm) =>{
            const label = $(elm).children()[1];
            $(label).removeClass('filter-selected');
            $(label).addClass('filter-not-selected');
        })
    }


    $(inputs).click((e) => {
        //console.log('clicked');
        const target = e.target;
        const parent = $(target).parent();
        const tab = $(parent).children()[1];
        resetFilterTabs();
        $(tab).removeClass('filter-not-selected');
        $(tab).addClass('filter-selected');
        let filter = $(target).attr('data-id');
        makeAjaxCall(filter);
    });


});
