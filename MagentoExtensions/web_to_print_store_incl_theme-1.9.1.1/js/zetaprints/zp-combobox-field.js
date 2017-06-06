(function ($) {

$.fn.combobox = function () {
  var $select = $(this);
  var $selected_option = $select.children(':selected');

  var options = [];

  $select.children('option').each(function() {
      options.push($(this).text());
  });

  var $wrapper = $select.parent();

  setTooltip($wrapper, $select.attr('title'), '(Select or enter a value)');

  var $field = $('<input />')
    .attr('id', $select.attr('id'))
    .attr('name', $select.attr('name'))
    .attr('class', 'input-text ' + $select.attr('class'))
    .insertAfter($select)
    .val($selected_option.text() ? $selected_option.text() : '')
    .autocomplete({
      appendTo: $wrapper,
      delay: 0,
      minLength: 0,
      position: { my: 'left top',
                  offset: '0',
                  at: 'left bottom',
                  of: $wrapper,
                  collision: 'none' },
      source: options,

      open: function (event, ui) {
        $field.parent().parent().addClass('z-index-1');
        $button.addClass('opened');
      },

      close: function (event, ui) {
        $field.parent().parent().removeClass('z-index-1')
        $button.removeClass('opened');
      }
    })
    .wrap('<div class="zp-combobox-input-wrapper"/>');

  $select.remove();

  var $button = $('<div class="zp-combobox-button">' +
                    '<div class="zp-combobox-icon-wrapper">' +
                      '<div class="zp-combobox-button-icon" />' +
                    '</div>' +
                  '</div>')
    .click(function () {
      if ($field.autocomplete('widget').is(':visible'))
        $field.autocomplete('close').focus();
      else
        $field.autocomplete('search', '').focus();
    })
    .appendTo($wrapper);

  return $field;
};

function setTooltip($element, title, tooltip) {
  var content = '';

  if ($.trim(title))
    content += title;

  if ($.trim(tooltip)){
    if (content)
      content += '<br/>';

    content += '<small>' + tooltip + '</small>';
  }

  if (content)
    $element.qtip({
      content: content,
      position: { corner: { target: 'topLeft', tooltip: 'bottomLeft' } },
      show: { delay: 1, solo: true, when: { event: 'focus' } },
      hide: { when: { event: 'unfocus' } }
    });
}

})(jQuery);

