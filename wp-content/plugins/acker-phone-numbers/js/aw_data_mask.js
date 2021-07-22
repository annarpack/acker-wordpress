jQuery(document).ready(function($) {
  const phoneObj = $('.phone-number');
  const err_div = $('#phone-err-msg');
  console.log('err_div', err_div);

  const options = {
    onComplete: function(val) {
      if( val.includes('555') || val.includes('800') ){
        err_div.removeClass('hidden').addClass('show');
      } else {
        err_div.removeClass('show').addClass('hidden');
      }
    },
    onChange: function(val, e, f, options){
      let valid = false;
      if(valid === false ) {
        err_div.removeClass('hidden').addClass('show');
      }
      if( (val.length > 0 && val.length < 17) || val.includes('555') ) {
        err_div.removeClass('hidden').addClass('show');
      }
    }
  }
  
  for(var i=0; i < phoneObj.length; i++){
    $(phoneObj[i]).mask('+1 (999) 999-9999', options);
  }
    $("#country").selectmenu({
      change: (event, data) => {
        if(data.item.value === "+1"){
          for(var i=0; i < phoneObj.length; i++){
            $(phoneObj[i]).mask('+44 0000 000000', options);
          }
          for(var i=0; i < phoneObj.length; i++){
            $(phoneObj[i]).mask('+1 (000) 000-0000', options);
          }

        }
        if(data.item.value === "+44"){
          for(var i=0; i < phoneObj.length; i++){
            $(phoneObj[i]).unmask('+1 (000) 000-0000', options);
          }
          for(var i=0; i < phoneObj.length; i++){
            $(phoneObj[i]).mask('+44 0000 000000', options);
          }
        }
        if(data.item.value === "+86"){
          for(var i=0; i < phoneObj.length; i++){
            $(phoneObj[i]).unmask('+1 (000) 000-0000', options);
          }
          for(var i=0; i < phoneObj.length; i++){
            $(phoneObj[i]).mask('+86 0000 000000', options);
          }
        }
      }
    });
})
