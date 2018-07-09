(function ($) {
  Drupal.behaviors.comunicaitalia_scrolling = {
    attach: function (context, settings) {

      /* SCROLL DOWN FOR FRONTPAGE */
      $('.scroll-down-wrapper').click(function() {
        $("html, body").animate({scrollTop: $('#block-six8theme-views-block-linee-guida-list-block-1 h2').offset().top}, 450, 'swing');
      });

      /* SCROLL DOWN FOR BASIC PAGES - GENERAL */
      $('.scroll-down-basic-wrapper').click(function() {
        $("html, body").animate({scrollTop: $('.main-wrapper').offset().top}, 450, 'swing');
      });

      /* SCROLL DOWN FOR GUIDELINES PAGE */
      if($('body').hasClass('page-node-21')) {
        $('.scroll-down-basic-wrapper').click(function() {
          $("html, body").animate({scrollTop: $('#block-six8theme-views-block-linee-guida-list-block-2').offset().top}, 450, 'swing');
        });
      }

      /* SCROLL DOWN FOR ROADMAP PAGE */
      if($('body').hasClass('path-roadmap')) {
        // $('.scroll-down-basic-wrapper').click(function() {
          console.log($('#block-six8theme-content'));
        //   $("html, body").animate({scrollTop: $('#block-six8theme-content').offset().top}, 450, 'swing');
        // });
      }

      /* SCROLL TOP FOR FOOTER */
      $('.scroll-top-wrapper').click(function() {
        $("html, body").animate({scrollTop: 0}, 600, 'swing');
      });

    }
  };
})(jQuery);