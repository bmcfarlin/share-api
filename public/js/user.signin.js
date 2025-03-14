(function($){

  var rvalidator;

  function bindHandlers(){
    trace('bindHandlers');
    $('form').submit(function(){
      return $(this).valid();
    });
    $('input[type=text],input[type=password]').keypress(function(e) {
      if (e.which == 13) {
        $('input[type=submit]').trigger('click');
        e.preventDefault();
        return false;
      }
    });
  }

  function init(){
    trace('init');
    rvalidator = $('form#signin').validate({
      onsubmit: false,
      onkeyup: false,
      errorClass: 'error',
      focusInvalid: false,
      rules: {
        email:{required:true,maxlength:100,email:false},
        password:{required:true}
      },
      messages:{
        email:{required:'Email Address is required'},
        password:{required:'Password is required'}
      }
    });
    $('input[type=text],input[type=password]').val('');
    $('#email').focus();
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
