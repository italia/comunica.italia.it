(function ($) {
  Drupal.behaviors.comunicaitalia_scrolling = {
    attach: function (context, settings) {

      if($('body').hasClass('path-frontpage')) {
        /* SCROLL DOWN FOR FRONTPAGE */
        $('.field--name-dynamic-token-fieldnode-scroll-down-arrow').click(function() {
          $("html, body").animate({scrollTop: $('#block-six8theme-views-block-linee-guida-list-block-1 h2').offset().top}, 450, 'swing');
        });
      } else if($('body').hasClass('path-roadmap')) {
        /* SCROLL DOWN FOR ROADMAP PAGE */
        $('.scroll-down-basic-wrapper').click(function() {
          $("html, body").animate({scrollTop: $('footer').offset().top}, 450, 'swing');
        });
      } else if($('body').hasClass('page-node-type-kit')) {
        /* SCROLL DOWN FOR SINGLE KIT PAGE */
        $('.field--name-dynamic-token-fieldnode-scroll-down-arrow').click(function() {
          $("html, body").animate({scrollTop: $('.field-name-body').offset().top}, 450, 'swing');
        });
        $('.scroll-down-kit-wrapper').click(function() {
          $("html, body").animate({scrollTop: $('.field-name-field_kit_files').offset().top}, 450, 'swing');
        });
      } else if($('body').hasClass('page-node-21')) {
        /* SCROLL DOWN FOR GUIDELINES PAGE */
        $('.field--name-dynamic-token-fieldnode-scroll-down-arrow').click(function() {
          $("html, body").animate({scrollTop: $('#block-six8theme-views-block-linee-guida-list-block-2').offset().top}, 450, 'swing');
        });
      } else if($('body').hasClass('page-node-26')) {
        /* SCROLL DOWN FOR CONTATTI PAGE */
        $('.field--name-dynamic-token-fieldnode-scroll-down-arrow').click(function() {
          $("html, body").animate({scrollTop: $('.wrapper').offset().top}, 450, 'swing');
        });
      } else {
        /* SCROLL DOWN FOR BASIC PAGES - GENERAL */
        $('.field--name-dynamic-token-fieldnode-scroll-down-arrow').click(function() {
          $("html, body").animate({scrollTop: $('.main-wrapper').offset().top}, 450, 'swing');
        });
      }

      /* SCROLL TOP FOR FOOTER */
      $('.scroll-top-wrapper').click(function() {
        $("html, body").animate({scrollTop: 0}, 600, 'swing');
      });

    }
  };
})(jQuery);