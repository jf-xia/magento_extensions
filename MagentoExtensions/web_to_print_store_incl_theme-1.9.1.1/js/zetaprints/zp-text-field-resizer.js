(function ($) {

  function restore_field_style (event) {
    var $field = $(this);
    var data = $field.data('text-field-resizer')

    $field.unbind(event);

    if (data['style'] == undefined)
      data['wrapper'].removeAttr('style');
    else
      data['wrapper'].attr('style', data['style']);
  }

  $.fn.text_field_resizer = function () {
    return this.each(function () {
      var $wrapper = $(this);
      var $field = $wrapper.find('.input-text, textarea');

      $wrapper.resizable({
        handles: $field.attr('tagName').toUpperCase() == 'TEXTAREA'
                   ? 'se, sw' : 'e, w',

        create: function () {
          $wrapper.mousedown(function () {
            $field.focus();
          });

          $field.data('text-field-resizer',
                      { 'style': $wrapper.attr('style'),
                        'wrapper': $wrapper } );
        },

        start: function () {
          $wrapper.css('z-index', 1000);
          $field.focus();
        },

        stop: function () {
          $field.focus();
        }
      })

      $wrapper
        .mouseenter(function () {
          $field.unbind('blur', restore_field_style);
        })
        .mouseleave(function () {
          $field.bind('blur', restore_field_style);
        });

    });
  }
})(jQuery);

