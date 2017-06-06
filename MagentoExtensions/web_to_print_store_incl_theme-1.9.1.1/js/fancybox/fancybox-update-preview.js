function fancybox_add_update_preview_button ($, zp) {
  //Don't add the button if it exists
  if ($('#zp-update-preview-button').length)
    return;

  var $outer = $('#fancybox-outer');

  var $update_preview = $('<a id="zp-update-preview-button">' +
                            '<span class="icon left-part">' +
                              '<span class="icon arrows" />' +
                            '</span>' +
                            '<span class="text">' +
                              '<span>' + update_preview_button_text + '</span>' +
                            '</span>' +
                          '</a>').appendTo($outer);

  var $fancybox_resize = $('#fancybox-resize')

  if ($fancybox_resize.length)
    $fancybox_resize.addClass('middle-position');
  else {
    $('#fancybox-close').addClass('resizer-tweaks');
    $update_preview.addClass('no-middle');
  }

  $update_preview.click(function () {
    if (!$outer.hasClass('modified'))
      return false;

    $outer
      .find('#fancybox-img')
      .bind('load.update-preview', function (event) {
        $(this).unbind('load.update-preview');

        $outer.removeClass('preview-updating');

        $('#fancybox-content')
          .bind('mousemove.zp-show-shapes', function (event) {
            $(this).unbind(event);

            $outer.removeClass('zp-hide-shapes');
        });
      });


    $outer.addClass('preview-updating zp-hide-shapes');

    zp.update_preview({ data: { zp: zp } });
  })
}

function fancybox_remove_update_preview_button ($) {
  $('#zp-update-preview-button').remove();
  $('#fancybox-resize').removeClass('middle-position');
  $('#fancybox-outer').removeClass('preview-updating zp-hide-shapes')
  $('#fancybox-content').unbind('mousemove.zp-show-shapes');
  $('#fancybox-img').unbind('load.update-preview');
}

