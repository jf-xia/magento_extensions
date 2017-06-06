function fancybox_add_use_image_button ($, zp, in_preview) {
  //Don't add the button if it exists
  if ($('#zp-select-image-button').length)
    return;

  var $outer = $('#fancybox-outer');

  var $button = $('<a id="zp-select-image-button">' +
                    '<span class="icon left-part">' +
                      '<span class="icon tick" />' +
                    '</span>' +
                    '<span class="text">' +
                      '<span class="use-image-text">' +
                        use_image_button_text +
                      '</span>' +
                      '<span class="selected-image-text">' +
                        selected_image_button_text +
                      '</span>' +
                    '</span>' +
                  '</a>').appendTo($outer);

  var $close = $('#fancybox-close').addClass('resizer-tweaks');

  if (in_preview) {
    $close
      .clone()
      .css('display', 'inline')
      .click(function () {
        var shape_name
          = $('.zetaprints-images-selector')
              .not('.minimized')
              .find(' > .selector-content > .tabs-wrapper > .images-scroller')
              .find('a[href="' + $('#fancybox-img').attr('src') + '"]')
              .parent()
              .children('input')
              .attr('name')
              .substring(12);

        $('#zetaprints-preview-image-container')
          .find(' > .zetaprints-field-shape[title="' + shape_name + '"] > .top')
          .click();

        $(this).remove();
        $close.attr('id', 'fancybox-close');
      })
      .appendTo($outer);

    $close.attr('id', 'fancybox-close-orig');
  }

  $button.addClass('no-middle')

  $button.click(function () {
    if ($outer.hasClass('selected'))
      return;

    var $input
      = $('.zetaprints-images-selector')
        .not('.minimized')
        .find(' > .selector-content > .tabs-wrapper > .images-scroller')
        .find('a[href="' +  $('#fancybox-img').attr('src') + '"]')
        .parent()
        .children('input')
        .attr('checked', true)
        .change();

    $outer.addClass('selected');

    if (in_preview) {
      var shape_name = $input.attr('name').substring(12);

      $('#zetaprints-preview-image-container')
        .find(' > .zetaprints-field-shape[title="' + shape_name + '"] > .top')
        .click();

      $('#fancybox-close').remove();
      $close.attr('id', 'fancybox-close');
    }
  })
}

function fancybox_update_preview_button ($) {
  $('#fancybox-close').addClass('resizer-tweaks');

  var is_checked
    = $('.zetaprints-images-selector')
        .not('.minimized')
        .find(' > .selector-content > .tabs-wrapper > .images-scroller')
        .find('a[href="' + $('#fancybox-img').attr('src') + '"]')
        .parent()
        .children('input')
        .attr('checked');

  if (is_checked)
    $('#fancybox-outer').addClass('selected');
  else
    $('#fancybox-outer').removeClass('selected');
}

function fancybox_remove_use_image_button ($) {
  $('#zp-select-image-button').remove();
}

