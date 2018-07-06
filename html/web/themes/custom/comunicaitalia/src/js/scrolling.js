(function ($) {
  Drupal.behaviors.comunicaitalia_scrolling = {
    attach: function (context, settings) {

      $('.scroll-down-wrapper').click(function() {
        $("html, body").animate({scrollTop: $('#block-six8theme-views-block-linee-guida-list-block-1 h2').offset().top}, 450, 'swing');
      });

      $('.scroll-top-wrapper').click(function() {
        $("html, body").animate({scrollTop: 0}, 600, 'swing');
      });

    }
  };
})(jQuery);