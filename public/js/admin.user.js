(function($){

  function bindHandlers(){
    trace('bindHandlers');

    $('input#account_no').keydown(function(event){
      if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 188 ) return;
      else if (event.keyCode > 47 && event.keyCode < 58 ) return;
      else if (event.keyCode > 95 && event.keyCode < 106 ) return;
      else event.preventDefault();
    });

    $('.filter-table input[type=text]').keydown(function(event){
      if (event.keyCode == 13){
        $('.filter-table input[type=button]').trigger('click');
      }
    });

    $('#password_reset').click(function(){
      $('#new_password').val('');
      if($(this).is(':checked')){
        $('#new_password').show();
      }else{
        $('#new_password').hide();
      }
    });

    $('#password').focus(function(){
      if($(this).val() == '********') $(this).val('');
    }).blur(function(){
      if($(this).val() == '') $(this).val('********');
    });

  }

  function init(){
    trace('init');
  }

  $(document).ready(function(){
    trace('document.ready');
    bindHandlers();
    init();
  });

  $(window).on('load', function(){
    trace('window.load');
  });

})(jQuery);
