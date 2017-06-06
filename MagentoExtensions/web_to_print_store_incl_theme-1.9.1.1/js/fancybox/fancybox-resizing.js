/**
 * Fancybox resizing
 *
 * Adds resizing capabilities to fancybox for those occasions
 * where borwser window is smaller than needed for to display
 * entire image size. Maximizing the image should expand it to its full
 * size. Restore should bring it back to its previous size.
 * If browser window size is changed after fancybox is shown, that will
 * not update restore dimensions.
 * Restore dimensions WILL be updated when fancybox is closed and
 * reopened.
 */

function fancybox_resizing_add (opts) {
  // ref to displayed image
  var $img = jQuery('#fancybox-img');

  // actual dimensions
  var height = $img.height();
  var width = $img.width();

  // displayed dimensions
  var img_orig_width = opts.width;
  var img_orig_height = opts.height;

  var $parent = jQuery('#fancybox-resize');

  // check if displayed size is smaller than loaded image
  // if it is, add max/restore button, do it if fancybox loads image only;
  // if needed will enable for
  // other tyes too, but they will need to have defined width and height
  if (img_orig_width > width && opts.type == 'image' && !$parent.length) {
    var $outer = jQuery('#fancybox-outer'); // get outer container

    // get reference of resizer container
    $parent = jQuery('<div id="fancybox-resize">' +
                       '<a class="maximize" style="display: none;"></a>' +
                       '<a class="restore" style="display: none;"></a>' +
                     '</div>');

    // add resizer to outer
    $outer.append($parent);

    // inject some usefull data in it
    // (original image dimensions and current dimensions)
    $parent.data({ 'height': height,
                   'width': width,
                   'img_orig_width': img_orig_width,
                   'img_orig_height': img_orig_height });

    // update close icon to use custom background
    // jQuery('#fancybox-close').css('background-position', '-68px -200px');
    jQuery('#fancybox-close').addClass('resizer-tweaks');

    // cycle 'maximize'/'restore' links
    jQuery('a', $parent).each(function () {
      // if it is maximize show it
      if (jQuery(this).hasClass('maximize'))
        jQuery(this).show();

      // add click handler
      jQuery(this).click(function () {
        var $this = jQuery(this); // get ref to clicked link

        $this.hide(); // hide it

        var data = jQuery('#fancybox-resize').data(); // get stored data

        // calculate difference in real and displayed dimensions
        var diff_x = data.img_orig_width - data.width;
        var diff_y = data.img_orig_height - data.height;

        if ($this.hasClass('maximize')) { // if we are maximizing
          fancybox_resizing_resize(diff_x, diff_y); // add diff
          jQuery('a.restore', $parent).first().show(); // show restore link
        } else if ($this.hasClass('restore')) { // if we are restoring
          fancybox_resizing_resize(-diff_x, -diff_y); // subtract diff
          jQuery('a.maximize', $parent).first().show(); // show miximize link
        }
      });
    });
  } else if ($parent.length > 0 && img_orig_height == height) {
    // there is a resizer
    // image and Fancybox are now with exatly same dimensions
    $parent.remove(); // remove resizer

    // reset close icon
    // jQuery('#fancybox-close').css('background-position', '-40px 0px');
    jQuery('#fancybox-close').removeClass('resizer-tweaks');
  } else if (img_orig_height > height) {
    // we have already created resizer and
    // this is just reopening of the fancybox
    // we are updating data in resizer to acommodate for cases where browser
    // window has been resized in mean time and 'restore' dimensions are changed
    $parent
      .data({ 'width': width,
              'height': height,
              'img_orig_width': img_orig_width,
              'img_orig_height': img_orig_height })
      .show();

    // make sure that 'maximize' handle is visible and restore hidden
    jQuery('a.restore', $parent).hide();
    jQuery('a.maximize', $parent).show();
  }
}

/**
 * Hide resize icons
 *
 * Does not do much it is just conveniance method
 * to use in onCleanup hook of fancy box to hide resizing
 * icons with close icon.
 */
function fancybox_resizing_hide () {
  jQuery('#fancybox-resize').hide();
  jQuery('#fancybox-close').removeClass('resizer-tweaks');
}

/**
 * Do resize
 *
 * Perform actual resizing by adding differences to various
 * elements of fancybox.
 * For out case we need to alter only wrap and inner container, if
 * resizing is to be used with other options enabled,
 * such as title, overlay etc.
 * this is the place to add other calculations.
 * Difference is always added so when restoring,
 * we need to pass negative difference.
 *
 * @param diff_x integer
 * @param diff_y integer - difference in pixels
 */
function fancybox_resizing_resize (diff_x, diff_y) {
  // wrap height is set to auto by default so we need to update only width
  var $wrap = jQuery('#fancybox-wrap');

  $wrap.width($wrap.width() + diff_x);

  // the above is true for fancybox 1.3.4,
  //but not guaranteed for earlier versions, so:
  $wrap.height($wrap.height() + diff_y);

  // get image container, it has both width and height set explicitly
  var $container = _fancybox_get_content_container();

  // add diffs to it.
  $container.width($container.width() + diff_x);
  $container.height($container.height() + diff_y);

  // center it to page
  jQuery.fancybox.center(true);
}

/**
 * Aparently between 1.3.1 and 1.3.4(current) versions of fancybox
 * there is change of id for immidiate image container.
 * This function tries to handle this.
 *
 * @returns Object jQuery object representing image container
 */
function _fancybox_get_content_container()
{
  return jQuery('#fancybox-content, #fancybox-inner');
}
