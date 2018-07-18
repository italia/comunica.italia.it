(function ($) {
  Drupal.behaviors.comunicaitalia_lineeguida = {
    attach: function (context, settings) {

      if($('body').hasClass('path-frontpage')) {
        $('.view-id-linee_guida_list').each(function() {
          var url = $(this).find('.views-field-field-call-to-action-home a').attr('href');
          $(this).find('.views-field-field-icona').click(function() {
            window.open(url,"_self");
          });
        });
      }

    }
  };
})(jQuery);