(function ($) {
  Drupal.behaviors.comunicaitalia_menu = {
    attach: function (context, settings) {

      var body = $('body');
      var topMenu = $('.link-list');
      var opacityLayer = $('.opacity-layer');

      /* RESPONSIVE - TOP MENU */
      $('.icon-menu-top').click(function() {
        opacityLayer.addClass('show');
        topMenu.addClass('open');
        body.addClass('helper-noscroll');
      });
      $('.close-menu-top').click(function() {
        topMenu.removeClass('open');
        opacityLayer.removeClass('show');
        body.removeClass('helper-noscroll');
      });

    }
  };
})(jQuery);