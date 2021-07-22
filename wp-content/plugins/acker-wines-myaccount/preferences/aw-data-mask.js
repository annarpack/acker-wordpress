jQuery(document).ready(function($) {
  const phoneObj = $('.phone-number');
  const countryObj = $(".country");

    const options = {
        onComplete: function(val, e) {
            const err_msg = $(e.target).parent().children()[1];
            if( val.includes('555') || val.includes('800') ){
                $(err_msg).removeClass('hidden').addClass('show');
            } else {
                $(err_msg).removeClass('show').addClass('hidden');
            }
        },
        onChange: function(val, e, f, options){
            const err_msg = $(e.target).parent().children()[1];
            let valid = false;
            if(valid === false ) {
                $(err_msg).removeClass('hidden').addClass('show');
            }
            if( (val.length > 0 && val.length < 17) || val.includes('555') ) {
                $(err_msg).removeClass('hidden').addClass('show');
            }
        }
    }
    const applyMask = (elm, code) => {
        if(code === "+1"){
            $(elm).mask('+1 (999) 999-9999', options);
        }
        if(code === "+44"){
            $(elm).mask('+44 0000 000000', options);
        }
        if(code === "+86"){
            $(elm).mask('+86 0000 000000', options);
        }
    }
    $(countryObj).selectmenu({
        change: (event, data) => {
                const value = data.item.value;
                const key = $(event.target).attr('data-value');
                const input = $(`#${key}`);
                applyMask(input, value);
            }
    })
    $(window).on('load',  () => {
        $(phoneObj).each( (i, elm) => {
            let country = $(elm).attr('data-value');
            if(country === undefined || country === '/' || country === '' || country === null){
                country = "+1";
            }
            applyMask(elm, country);
        })
    })
})
