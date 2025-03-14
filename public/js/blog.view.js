(function($){

  function bindHandlers(){
    trace('bindHandlers');
  }

  function init(){
    trace('init');
    $('div.article div.photo').each(function(i, o){
      var element = $(this);
      var src = element.data('src');
      element.css('background-image', 'url(' + src + ')');
    });
  }

  $(document).ready(function(){
    trace('document.ready');
    bindHandlers();
    init();
  });

  $(window).on('load', function(){
    trace('window.load');
    $("#share").jsSocials({shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest"] });
  });

})(jQuery);

