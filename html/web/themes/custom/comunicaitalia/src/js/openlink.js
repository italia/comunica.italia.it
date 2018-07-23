(function ($) {
  Drupal.behaviors.comunicaitalia_openlink = {
    attach: function (context, settings) {

      if($('body').hasClass('path-frontpage')) {
        /* TARGET BLANK FOR FRONTPAGE PAGE */
        openBlank('.view-linee-guida-list .views-field-field-call-to-action-pagina a');
      } else if($('body').hasClass('path-roadmap')) {
        /* TARGET BLANK FOR ROADMAP PAGE */

      } else if($('body').hasClass('page-node-type-kit')) {
        /* TARGET BLANK FOR KIT PAGE */

      } else if($('body').hasClass('page-node-21')) {
        /* TARGET BLANK FOR GUIDELINE PAGE */
        openBlank('.view-linee-guida-list .field-name-field_link a');
        openBlank('.view-linee-guida-list .field-name-field_call_to_action_pagina a');

      } else if($('body').hasClass('page-node-26')) {
        /* TARGET BLANK FOR CONTATTI PAGE */
        openBlank('.field-name-field_link .field__item a');
        openBlank('.field-name-field_social .field__item a');
        openBlank('.field-name-field_link_agid a');
        openBlank('.field-name-field_call_to_action_pagina a');

      } else {
        /* SCROLL DOWN FOR BASIC PAGES - GENERAL */

      }

      function openBlank(selector) {
        $(selector).click(function(e) {
          e.preventDefault();
          var url = $(this).attr('href');
          window.open(url, '_blank');
        });
      }

    }
  };
})(jQuery);