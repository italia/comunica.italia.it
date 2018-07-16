(function ($) {
  Drupal.behaviors.comunicaitalia_menu = {
    attach: function (context, settings) {

      var body = $('body');
      var topMenu = $('.link-list');
      var rightSection = $('.top-menu .right-section');
      var mainMenu = $('.block-id-six8theme_main_menu');
      var regionNavigation = $('.region-navigation');
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

      /* RESPONSIVE - MAIN MENU */
      $('.icon-menu-main').click(function() {
        opacityLayer.addClass('show');
        mainMenu.addClass('open');
        body.addClass('helper-noscroll');
      });
      $('.close-menu-main').click(function() {
        mainMenu.removeClass('open');
        opacityLayer.removeClass('show');
        body.removeClass('helper-noscroll');
      });

      /* CHANGING STRUCTURE FOR RESPONSIVE */
      if (matchMedia) {
        var mq = window.matchMedia("(max-width: 768px)");
        mq.addListener(WidthChange);
        WidthChange(mq);
      }

      function WidthChange(mq) {
        if (mq.matches) {
          setResponsive();
        } else {
          unsetResponsive();
        }
      }

      function setResponsive() {
        topMenu.detach();
        mainMenu.detach();
        body.prepend(topMenu);
        body.prepend(mainMenu);
      }

      function unsetResponsive() {
        topMenu.detach();
        mainMenu.detach();
        rightSection.append(topMenu)
        regionNavigation.append(mainMenu);
      }

    }
  };
})(jQuery);