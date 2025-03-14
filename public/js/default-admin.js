(function($){

  function bindHandlers(){
    $(document).keypress(function(e) {
      if(e.which == 110){
        if(e.ctrlKey){
          var href = $('a.new').attr('href');
          href = href.replace('javascript:', '');
          eval(href);
          return false;
        }
      }else if(e.which == 13) {
        var l = $('a.save').length;
        if(l == 1){
          var href = $('a.save').attr('href');
          href = href.replace('javascript:', '');
          eval(href);
          return false;
        }
      }
    });

    $(window).scroll(function(){
      var p1 = {};
      var st = $(window).scrollTop();
      p1.top = st > 27? st - 27 : 27;

      var p2 = {};
      p2.queue = false;
      p2.duration = 333;

      $('.command-float').animate(p1,p2);
    });
  }

  $(document).ready(function(){
    bindHandlers();
  });

  $(window).on('load', function(){
    $("input:text, textarea").eq(0).focus();
  });

})(jQuery);


function __postback(cmd, cmd_arg, cmd_action, cmd_target){
  var f = $('#form');
  f.attr('target', '_self');
  if(cmd) f.append($('<input />').attr('type', 'hidden').attr('name', 'cmd').attr('value', cmd));
  if(cmd_arg) f.append($('<input />').attr('type', 'hidden').attr('name', 'cmd_arg').attr('value', cmd_arg));
  if(cmd_action)f.attr('action', cmd_action);
  if(cmd_target)f.attr('target', cmd_target);

  var v = f.validate({onfocusout: true});
  var b = true;

  if(cmd == 'Cancel'){
    v.cancelSubmit = true;
  }else{
    b = f.valid();
  }
  if(b){
    if(!f.attr('doubleSubmit')){
      if(f.attr('target') == '_self') f.attr('doubleSubmit', true);
      f.submit();
    }
  }
}

function jConfirmDelete(o){
  nsource = $(o);
  jConfirm('Are your sure?', 'Confirm Delete', jConfirmHandler);
  return false;
}
function jConfirmHandler(b){
  if(b){
    var cmd = nsource.attr('href');
    if(cmd.indexOf("javascript:") == 0){
      cmd = cmd.replace("javascript:", "");
      window.setTimeout(cmd, 0);
    }else{
      window.location.href = cmd;
    }
  }
}

function trace(s){
  if(window.console)window.console.log(s);
}


