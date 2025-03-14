(function($){

  function bindHandlers(){
    trace('common.bindHandlers');
  }

  function init(){
    trace('common.init');
  }

  $(document).ready(function(){
    trace('common.document.ready');
    bindHandlers();
    init();
  });

  $(window).on('load', function(){
    trace('common.window.load');
  });

})(jQuery);


function trace(s){
  if(typeof(s) == 'object') s = JSON.stringify(s);
  if(window.console) window.console.debug(s);
}
