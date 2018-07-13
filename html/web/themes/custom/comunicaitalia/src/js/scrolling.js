(function ($) {
  Drupal.behaviors.comunicaitalia_scrolling = {
    attach: function (context, settings) {

      /* SCROLL DOWN FOR FRONTPAGE */
      $('.scroll-down-wrapper').click(function() {
        $("html, body").animate({scrollTop: $('#block-six8theme-views-block-linee-guida-list-block-1 h2').offset().top}, 450, 'swing');
      });

      if($('body').hasClass('path-roadmap')) {
        /* SCROLL DOWN FOR ROADMAP PAGE */
        $('.scroll-down-basic-wrapper').click(function() {
          console.log($('#block-six8theme-content').offset().top);
          $("html, body").animate({scrollTop: $('footer').offset().top}, 450, 'swing');
        });
      } else if($('body').hasClass('page-node-type-kit')) {
        /* SCROLL DOWN FOR SINGLE KIT PAGE */
        $('.scroll-down-basic-wrapper').click(function() {
          $("html, body").animate({scrollTop: $('.field-name-body').offset().top}, 450, 'swing');
        });
        $('.scroll-down-kit-wrapper').click(function() {
          $("html, body").animate({scrollTop: $('.field-name-field_kit_files').offset().top}, 450, 'swing');
        });
      } else if($('body').hasClass('page-node-21')) {
        /* SCROLL DOWN FOR GUIDELINES PAGE */
        $('.scroll-down-basic-wrapper').click(function() {
          $("html, body").animate({scrollTop: $('#block-six8theme-views-block-linee-guida-list-block-2').offset().top}, 450, 'swing');
        });
      } else if($('body').hasClass('page-node-26')) {
        /* SCROLL DOWN FOR CONTATTI PAGE */
        $('.scroll-down-basic-wrapper').click(function() {
          $("html, body").animate({scrollTop: $('.field-name-body').offset().top}, 450, 'swing');
        });
      } else {
        /* SCROLL DOWN FOR BASIC PAGES - GENERAL */
        $('.scroll-down-basic-wrapper').click(function() {
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