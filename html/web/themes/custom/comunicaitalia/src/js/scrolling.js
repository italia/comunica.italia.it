(function ($) {
  Drupal.behaviors.comunicaitalia_scrolling = {
    attach: function (context, settings) {

      $('.scroll-top-wrapper').click(function() {
        $("html, body").animate({scrollTop:0}, 600, 'swing');
      });

    }
  };
})(jQuery);