(function ($) {
  Drupal.behaviors.comunicaitalia_language = {
    attach: function (context, settings) {

      var $document = $(document),
          languageArrow = $('.language__arrow'),
          $languageActive = $('.language-switcher-language-url ul li.is-active'),
          $languageItem = $('.language-switcher-language-url ul li:not(.is-active)'),
          languageList = { it:"Italiano", en:"English" };

      $languageActive.prependTo($languageActive.parent());

      languageArrow.once('comunicaitalia_language').on('click', function () {
        if (languageArrow.hasClass('active')) {
          languageArrow.removeClass('active');
          $languageItem.hide();
        }
        else {
          languageArrow.addClass('active');
          var lang = $languageItem.attr('hreflang');
          $('.language-switcher-language-url ul li:not(.is-active) a').text(languageList[lang]);
          $languageItem.show();
        }

      });

      $document.once('comunicaitalia_language').click(function (event) {
        //if you click on anything except the modal itself or link close language select
        if (!$(event.target).closest(".language-switcher-language-url").length) {
          $languageItem.hide();
        }
      });
    }
  };
})(jQuery);