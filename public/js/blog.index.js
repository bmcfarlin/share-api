(function($){
  function bindHandlers(){
    trace('bindHandlers');
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