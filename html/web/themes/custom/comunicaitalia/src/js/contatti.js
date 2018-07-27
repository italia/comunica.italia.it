(function ($) {
  Drupal.behaviors.comunicaitalia_contatti = {
    attach: function (context, settings) {

      if($('body').hasClass('page-node-type-contatti')) {

        var link = $('.field-name-field_link_agid a');
        var logo = $('.field-name-field_logo_agenzia');
        var url = link.attr('href');

        logo.click(function() {
          window.open(url, '_blank');
        });
      }

    }
  };
})(jQuery);