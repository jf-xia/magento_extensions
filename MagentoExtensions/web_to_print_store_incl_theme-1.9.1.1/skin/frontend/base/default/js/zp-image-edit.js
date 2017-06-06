function zetaprint_image_editor ($) {
  var context = this;

  //console.log('context: ', context);

  var $container = $('div.zetaprints-image-edit');

  //!!! Temp solutions
  var imageEditorDelimeter = '&';

  load_image();

  var _cropVisualAssistant = new cropVisualAssistant ();

  _cropVisualAssistant.setUserImageThumb(context.$selected_thumbnail);
  _cropVisualAssistant.setTemplatePreview(context.placeholder, context.shape);

  var $info_bar = $container.find('div.info-bar');
  var $restore_button = $('#restore-button');

  var crop_data = null;

  var info_bar_elements = {
    'current': {
      'width': $('#current-width'),
      'height': $('#current-height'),
      'dpi': $('#current-dpi') },

    'recommended': {
      'width': $('#recommended-width'),
      'height': $('#recommended-height'),
      'dpi': $('#recommended-dpi') } };

  set_info_bar_value('recommended', 'width', context.placeholder.width);
  set_info_bar_value('recommended', 'height', context.placeholder.height);
  set_info_bar_value('recommended', 'dpi', _cropVisualAssistant.getPlaceholderInfo().resolution);

  var $user_image_container
                       = $container.find('div.zetaprints-image-edit-container');

  var $user_image = $('#zetaprints-image-edit-user-image');

  var container_size = {
    width: $user_image_container.width() - 2,
    height: $user_image_container.height() - 2
  }

  //Use this factor to convert container's dimension to
  //original one (multiply by the factor) or vice versa (divide by the factor)
  var container_to_placeholder_factor
        = get_factor_a_to_b(context.placeholder.width,
                            context.placeholder.height,
                            container_size.width,
                            container_size.height)

  //Same as container_to_placeholder_factor
  var container_to_image_factor = null;

  var thumb_to_container_factor = null;
  var image_dpi = null;
  var image_width = null;
  var frame_width = null;

function imageEditorCrop () {
  set_info_bar_warning();
  set_info_bar_state();

  //var cropMetadata = _cropVisualAssistant.getInitCroppedArea(0, 0);

  if (isCropFit) {
    var data = {
      selection: {
        position: {
          top: 0,
          left: 0 } },
      image: {
        position: {
          top: 0, left: 0 } } };

    //console.log('container_size.width', container_size.width);

    var width_factor
                  = context.placeholder.width / container_size.width;
    var height_factor
                = context.placeholder.height / container_size.height;

    container_to_image_factor
                  = width_factor > height_factor ? width_factor : height_factor;

    var metadata = context.$input.data('metadata');

    data.selection.width = Math.round(context.placeholder.width
                                                   / container_to_image_factor);
    data.selection.height = Math.round(context.placeholder.height
                                                   / container_to_image_factor);

    //console.log('data.selection.width', data.selection.width);

    //!!! Move to bottom
    frame_width = data.selection.width;

    //console.log(metadata);

    if (metadata) {
      var in_image = metadata['sh-x'] == 0 && metadata['sh-y'] == 0
                     && metadata['sz-x'] == 1 && metadata['sz-y'] == 1;

      //console.log('in_image:', in_image);

      //console.log('cr-x2: ', metadata['cr-x2'], ' cr-x1: ', metadata['cr-x1']);

      data.selection.width = (metadata['cr-x2'] - metadata['cr-x1'])
                                                         * data.selection.width;
      data.selection.height = (metadata['cr-y2'] - metadata['cr-y1'])
                                                        * data.selection.height;

      data.selection.position.left = metadata['cr-x1'] * data.selection.height;
      data.selection.position.top = metadata['cr-y1'] * data.selection.width;

      data.container = container_size;
    }

    //console.log('data.selection.width', data.selection.width);

    if (metadata) {
      data.image.width = data.selection.width / metadata['sz-x'];
      data.image.height = data.selection.height / metadata['sz-y'];
    } else {
      data.image.width = Math.round(_cropVisualAssistant.userImage.widthActualPx
                                                   / container_to_image_factor);
      data.image.height = Math.round(
                            _cropVisualAssistant.userImage.heightActualPx /
                            container_to_image_factor);
    }

    //!!! Move to bottom
    image_width = data.image.width;

    //image_width = Math.round(_cropVisualAssistant.userImage.widthActualPx
    //                                               / container_to_image_factor);
    //var image_height = Math.round(_cropVisualAssistant.userImage.heightActualPx
    //                                               / container_to_image_factor);

    if (metadata) {
      data.image.position.left = data.selection.position.left +
                                        data.selection.width / metadata['sh-x'];
      data.image.position.top = data.selection.position.top +
                                       data.selection.height / metadata['sh-y'];
    }

    var width_factor = context.placeholder.width
                                 / _cropVisualAssistant.userImage.widthActualPx;
    var height_factor = context.placeholder.height
                                / _cropVisualAssistant.userImage.heightActualPx;

    var image_to_placeholder_factor
                  = width_factor < height_factor ? width_factor : height_factor;

    var resized_image_width = data.image.width * image_to_placeholder_factor;
    var resized_image_height = data.image.height * image_to_placeholder_factor;

    var dpi = Math.round(image_dpi / image_to_placeholder_factor);

    set_info_bar_value('current', 'dpi', dpi);

    if (dpi < _cropVisualAssistant.getPlaceholderInfo().resolution)
      set_info_bar_warning('small-image-warning');

    //var data = {
    //  selection: {
    //    width: frame_width,
    //    height: frame_height,
    //    position: {
    //      top: 0,
    //      left: 0 } },
    //  image: {
    //    width: resized_image_width,
    //    height: resized_image_height,
    //    position: {
    //      top: 0,
    //      left: 0 } } };

    //if (metadata) {
    //  var cr_x1 = metadata['cr-x1'];
    //  var cr_y1 = metadata['cr-y1'];
    //  var cr_x2 = metadata['cr-x2'];
    //  var cr_y2 = metadata['cr-y2'];

    //  if (cr_x1 && cr_y1 && cr_x2 && cr_y2) {
    //    data.selection = {
    //      width: (cr_x2 - cr_x1) * data.selection.width,
    //      height: (cr_y2 - cr_y1) * data.selection.height,
    //      position: {
    //        top: cr_y1 * data.selection.width,
    //        left: cr_x1 * data.selection.height } };

    //    data.container = user_image_container_size;
    //  }

    //  var selection_position = data.selection.position;
    //  var selection_size = {
    //    width: data.selection.width,
    //    height: data.selection.height };

    //  var sh_x = metadata['sh-x'];
    //  var sh_y = metadata['sh-y'];

    //  if (sh_x && sh_y)
    //    data.image.position = {
    //      left: selection_position.left + selection_size.width / sh_x,
    //      top: selection_position.top + selection_size.height / sh_y };

    //  var sz_x = metadata['sz-x'];
    //  var sz_y = metadata['sz-y'];

    //  if (sz_x && sz_y) {
    //    data.image.width = selection_size.width / sz_x;
    //    data.image.height = selection_size.height / sz_y;
    //  }
    //}
  } else {
    var data = {
      selection: {
        width: $user_image.width(),
        height: $user_image.height() } };

    //!!! Temp solution
    context.x = 0;
    context.y = 0;
    context.x2 = _cropVisualAssistant.userImage.widthActualPx;
    context.y2 = _cropVisualAssistant.userImage.heightActualPx;
    context.w = _cropVisualAssistant.userImage.widthActualPx;
    context.h = _cropVisualAssistant.userImage.heightActualPx;
  }

  $user_image.power_crop({
    simple: !isCropFit,
    data: data,
    crop: isCropFit ? cropping_callback : function () {},
    stop: crop_stopped_callback });

  //$('#imageEditorCropForm').show();
  //$('div.zetaprints-buttons-row').show();
}

  function imageEditorHideCrop() {
    $user_image.power_crop('destroy');

    //$('#imageEditorTooltip').hide();

    //$('div.zetaprints-buttons-row').hide();

    //$('#imageEditorCropForm').hide();

    //$('#imageEditorImageInfo').empty();
  }

  function crop_stopped_callback (data) {
    crop_data = data;



    var c = {
      x: data.selection.position.left,
      y: data.selection.position.top,
      x2: data.selection.position.left + data.selection.width,
      y2: data.selection.position.top + data.selection.height,
      w: data.selection.width,
      h: data.selection.height }

    //!!! Temp solution
    context.x = c.x / thumb_to_container_factor;
    context.y = c.y / thumb_to_container_factor;
    context.x2 = c.x2 / thumb_to_container_factor;
    context.y2 = c.y2 / thumb_to_container_factor;
    context.w = c.w;
    context.h = c.h;

    if (isCropFit) {
      var image_size = {
        width: data.image.width,
        height: data.image.height };

      var image_position = data.image.position;

      var mx = Number($user_image.width()) / image_size.width;
      var my = Number($user_image.height()) / image_size.height;

      var i = {
        x: image_position.left,
        y: image_position.top,
        x2: image_position.left + image_size.width,
        y2: image_position.top + image_size.height };

      if (c.x < i.x)
        c.x = 0;
      else
        c.x = (c.x - image_position.left) * mx;

      if (c.y < i.y)
        c.y = 0;
      else
        c.y = (c.y - image_position.top) * my;

      if (c.x2 > i.x2)
        c.x2 = $user_image.width();
      else
        c.x2 = (c.x2 - i.x) * mx;

      if (c.y2 > i.y2)
        c.y2 = $user_image.height();
      else
        c.y2 = (c.y2 - i.y) * my;

      _cropVisualAssistant.updateView([c.x, c.y, c.x2, c.y2]);
    }
  }

  function cropping_callback (data) {
    if (isCropFit) {
      var limited_image_width = limit_a_to_b(data.image.position.left,
                                           data.image.width,
                                           data.selection.position.left,
                                           data.selection.width);

      var limited_image_height = limit_a_to_b(data.image.position.top,
                                              data.image.height,
                                              data.selection.position.top,
                                              data.selection.height);

      if ((limited_image_height != data.image.height
           || limited_image_width != data.image.width)
          && limited_image_width != 0 && limited_image_height != 0) {

        var crop_width_factor = limited_image_width / data.image.width;
        var crop_height_factor = limited_image_height / data.image.height;

        var width = _cropVisualAssistant.userImage.widthActualPx * crop_width_factor;
        var height = _cropVisualAssistant.userImage.heightActualPx * crop_height_factor;

        var _image_width = width / container_to_image_factor;

        var frame_factor = data.selection.width / frame_width;

        var croped_image_width_factor = data.image.width / _image_width;
        var croped_factor = croped_image_width_factor / frame_factor;

        //var image_factor = data.image.width / image_width;
        //var factor = image_factor / frame_factor;

        //var width = croped_actual_width * croped_factor;
        //var height = croped_actual_height * factor;

        var dpi = Math.round(image_dpi / croped_factor);

        set_info_bar_warning();

        if (dpi < _cropVisualAssistant.getPlaceholderInfo().resolution)
          set_info_bar_warning('low-cropped-resolution-warning');

        set_info_bar_state('cropped', true);
      } else {
        var image_factor = data.image.width / image_width;
        var frame_factor = data.selection.width / frame_width;
        var factor = image_factor / frame_factor;

        var width = _cropVisualAssistant.userImage.widthActualPx;
        var height = _cropVisualAssistant.userImage.heightActualPx;

        var dpi = Math.round(image_dpi / factor);

        set_info_bar_warning();

        if (dpi < _cropVisualAssistant.getPlaceholderInfo().resolution)
          set_info_bar_warning('low-full-resolution-warning');

        set_info_bar_state('cropped', false);
      }

      //if (limited_image_width == 0)
      //  var limited_image_factor = image_factor
      //else
      //  var limited_image_factor = limited_image_width / image_width;

      set_info_bar_value('current', 'width', Math.round(width));
      set_info_bar_value('current', 'height', Math.round(height));

      //if (image.clipped == true || width > image.width
      //    || height > image.height) {

      //  var factor = data.selection.width / image_size.width;
      //  var dpi = Math.round(_cropVisualAssistant.getPlaceholderInfo().resolution * factor);

      //  var dpi = Math.round(width / _cropVisualAssistant.templateImage.widthIn);

      //  if (dpi < _cropVisualAssistant.getPlaceholderInfo().resolution)
      //    set_info_bar_warning('small-image-warning');
      //  else
      //    set_info_bar_warning();

      //} else {
      //  var dpi =  _cropVisualAssistant.getPlaceholderInfo().resolution;
      //  set_info_bar_state();
      //}

      set_info_bar_value('current', 'dpi', dpi);
    } else {
      //updateEditAndSaveInfoBar(c.w, c.h);
      //_cropVisualAssistant.updateInfoBar(c.w, c.h);
    }

    //setTimeout(imageEditorAdjustSize, 100);
  }

  /**
   * Perform crop
   */
  function imageEditorApplyCrop () {
    if (isCropFit) {
      storeCropMetadata();
      imageEditorHideCrop();
      $.fancybox.close();
    } else {
      //imageEditorHideCrop();
      $.fancybox.showActivity();
      applyCropServer();
    }
  }

  /**
   * Store crop metadata for further usage
   */
  function storeCropMetadata() {
    if (!(isCropFit && crop_data))
      return;

    var width = Number($user_image.width());
    var height = Number($user_image.height());

    var image_position = crop_data.image.position;
    var selection_position = crop_data.selection.position;

    //console.log('image_position ', image_position);
    //console.log('selection_position', selection_position);

    var image_size = {
      width: crop_data.image.width,
      height: crop_data.image.height };
    var selection_size = {
      width: crop_data.selection.width,
      height: crop_data.selection.height };

    //console.log('image_size ', image_size);
    //console.log('selection_size', selection_size);

    image_position.right = image_position.left + image_size.width;
    image_position.bottom = image_position.top + image_size.height;

    selection_position.right = selection_position.left + selection_size.width;
    selection_position.bottom = selection_position.top + selection_size.height;

    var in_image = image_position.left <= selection_position.left &&
                       image_position.right >= selection_position.right &&
                       image_position.top <= selection_position.top &&
                       image_position.bottom >= selection_position.bottom;

    var in_frame = image_position.left > selection_position.left &&
                   image_position.right < selection_position.right &&
                   image_position.top > selection_position.top &&
                   image_position.bottom < selection_position.bottom;

    //var metadata = {
    //  'cr-x1': 0,
    //  'cr-x2': 1,
    //  'cr-y1': 0,
    //  'cr-y2': 1 };

    if (in_image) {
      //console.log('selection_position.right ',  selection_position.right);
      //console.log('image_size.width ', image_size.width);

      //console.log('selection_position.left - image_position.left', selection_position.left - image_position.left)

      var metadata = {
        'cr-x1': (selection_position.left - image_position.left) / image_size.width,
        'cr-x2': (selection_position.right - image_position.left) / image_size.width,
        'cr-y1': (selection_position.top - image_position.top) / image_size.height,
        'cr-y2': (selection_position.bottom - image_position.top) / image_size.height,
        'x1': context.shape.x1,
        'y1': context.shape.y1,
        'x2': context.shape.x2,
        'y2': context.shape.y2 };
        //'sh-x': 0,
        //'sh-y': 0,
        //'sz-x': 1,
        //'sz-y': 1 };
    }

    if (in_frame) {
      var shift_x1 = image_position.left - selection_position.left;
      var shift_x1_in_page = shift_x1 / selection_size.width;

      //console.log('shift_x1: ', shift_x1, ' shift_x1_in_page: ', shift_x1_in_page);

      var shift_y1 = image_position.top - selection_position.top;
      var shift_y1_in_page = shift_y1 / selection_size.height;

      //console.log('shift_y1: ', shift_y1, ' shift_y1_in_page: ', shift_y1_in_page);

      var shift_x2 = image_position.right - selection_position.right;
      var shift_x2_in_page = shift_x2 / selection_size.width;

      //console.log('shift_x2: ', shift_x2, ' shift_x2_in_page: ', shift_x2_in_page);

      var shift_y2 = image_position.bottom - selection_position.bottom;
      var shift_y2_in_page = shift_y2 / selection_size.height;

      //console.log('shift_y2: ', shift_y2, ' shift_y2_in_page: ', shift_y2_in_page);

      var metadata = {
        'cr-x1': 0,
        'cr-x2': 1,
        'cr-y1': 0,
        'cr-y2': 1,
        'x1': context.shape.x1 + shift_x1_in_page,
        'y1': context.shape.y1 + shift_y1_in_page,
        'x2': context.shape.x2 + shift_x2_in_page,
        'y2': context.shape.y2 + shift_y2_in_page
      };
    }

    if (!in_image && !in_frame) {
      //if (selection_position.left > image_position.left
      //    && selection_position.left < image_position.right)
      //  image_position.left = selection_position.left;

      //if (selection_position.right > image_position.left
      //    && selection_position.right < image_position.right)
      //  image_position.right = selection_position.right;

      //if (selection_position.top > image_position.top
      //    && selection_position.top < image_position.bottom)
      //  image_position.top = selection_position.top;

      //if (selection_position.bottom > image_position.top
      //    && selection_position.bottom < image_position.bottom)
      //  image_position.bottom = selection_position.bottom;

      //console.log('selection_position.left', selection_position.left);
      //console.log('selection_position.right', selection_position.right);
      //console.log('selection_position.top', selection_position.top);
      //console.log('selection_position.bottom', selection_position.bottom);

      //console.log('image_position.left', image_position.left);
      //console.log('image_position.right', image_position.right);
      //console.log('image_position.top', image_position.top);
      //console.log('image_position.bottom', image_position.bottom);

      if (selection_position.left < image_position.left)
        selection_position.left = image_position.left

      if (selection_position.right > image_position.right)
        selection_position.right = image_position.right

      if (selection_position.top < image_position.top)
        selection_position.top = image_position.top

      if (selection_position.bottom > image_position.bottom)
        selection_position.bottom = image_position.bottom

      //console.log('selection_position.left', selection_position.left);
      //console.log('selection_position.right', selection_position.right);
      //console.log('selection_position.top', selection_position.top);
      //console.log('selection_position.bottom', selection_position.bottom);

      //image_size.width = image_position.right - image_position.left;
      //image_size.height = image_position.bottom - image_position.top;

      //console.log('image_position ', image_position);
      //console.log('selection_position', selection_position);

      //console.log('image_size ', image_size);
      //console.log('selection_size', selection_size);

      var shift_x1 = image_position.left - selection_position.left;
      var shift_x1_in_container = shift_x1 * container_to_placeholder_factor;
      var shift_x1_inch = shift_x1_in_container / image_dpi;
      var shift_x1_in_page = shift_x1_inch / context.page.width_in;

      var shift_y1 = image_position.top - selection_position.top;
      var shift_y1_in_container = shift_y1 * container_to_placeholder_factor;
      var shift_y1_inch = shift_y1_in_container / image_dpi;
      var shift_y1_in_page = shift_y1_inch / context.page.height_in;

      var shift_x2 = image_position.right - selection_position.right;
      var shift_x2_in_container = shift_x2 * container_to_placeholder_factor;
      var shift_x2_inch = shift_x2_in_container / image_dpi;
      var shift_x2_in_page = shift_x2_inch / context.page.width_in;

      var shift_y2 = image_position.bottom - selection_position.bottom;
      var shift_y2_in_container = shift_y2 * container_to_placeholder_factor;
      var shift_y2_inch = shift_y2_in_container / image_dpi;
      var shift_y2_in_page = shift_y2_inch / context.page.height_in;

      var metadata = {
        'cr-x1': (selection_position.left - image_position.left) / image_size.width,
        'cr-x2': (selection_position.right - image_position.left) / image_size.width,
        'cr-y1': (selection_position.top - image_position.top) / image_size.height,
        'cr-y2': (selection_position.bottom - image_position.top) / image_size.height,
        'x1': context.shape.x1 + shift_x1_in_page,
        'y1': context.shape.y1 + shift_y1_in_page,
        'x2': context.shape.x2 + shift_x2_in_page,
        'y2': context.shape.y2 + shift_y2_in_page
        //'sh-x': context.shape.x1 + shift_x_in_page,
        //'sh-y': context.shape.y1 + shift_y_in_page,
        //'sz-x': image_size.width / selection_size.width,
        //'sz-y': image_size.height / selection_size.height
      };
    }

    //else {

    //metadata['sh-x'] =
    //     selection_size.width / (image_position.left - selection_position.left);
    //metadata['sh-y'] =
    //      selection_size.height / (image_position.top - selection_position.top);

    //metadata['sz-x'] = selection_size.width / image_size.width;
    //metadata['sz-y'] =  selection_size.height / image_size.height;

    //}

    //console.log(metadata);

    context.$input.data('metadata', metadata);
  }

  function clearCropMetadata () {
    crop_data = null;
    context.$input.removeData('metadata');

    $('#' + _cropVisualAssistant.getUserImageThumbGuid()).each(function(){
      //$(this).data('metadata', null);
      $(this).prev('div.thumbCropedAreaToolSet').remove();
    });

    var image_width_in = _cropVisualAssistant.userImage.widthActualPx
                         / context.placeholder.width
                         * _cropVisualAssistant.templateImage.widthIn;
    image_dpi = Math.round(_cropVisualAssistant.userImage.widthActualPx
                                                              / image_width_in);

    set_info_bar_value('current', 'width',
                                  _cropVisualAssistant.userImage.widthActualPx);
    set_info_bar_value('current', 'height',
                                 _cropVisualAssistant.userImage.heightActualPx);
    set_info_bar_value('current', 'dpi', image_dpi);
  }

  /**
   * Apply image crop using ZetaPrint server
   */
  function applyCropServer() {
    $.ajax({
      url: context.url.image
           + '?CropX1=' + context.x + imageEditorDelimeter
           + 'CropY1=' + context.y + imageEditorDelimeter
           + 'CropX2=' + context.x2 + imageEditorDelimeter
           + 'CropY2=' + context.y2 + imageEditorDelimeter
           + 'page=img-crop' + imageEditorDelimeter
           + 'ImageID=' + context.image_id,
      type: 'POST',
      data: 'zetaprints-CropX1=' + context.x + imageEditorDelimeter
          + 'zetaprints-CropY1=' + context.y  + imageEditorDelimeter
          + 'zetaprints-CropX2=' + context.x2 + imageEditorDelimeter
          + 'zetaprints-CropY2=' + context.y2 + imageEditorDelimeter
          + 'zetaprints-action=img-crop' + imageEditorDelimeter
          + 'zetaprints-ImageID=' + context.image_id,
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert(zetaprints_trans('Can\'t crop image:') + ' ' + textStatus);
      },
      success: function (data, textStatus) {
        clearCropMetadata();
        imageEditorHideCrop();
        imageEditorApplyImage(data);
        //showImageEditorTooltip('Image Cropped');
        //$('#userImagePreview').fadeIn();
      }
    });
  }

  /**
   * Perform image restore
   */
  function imageEditorRestore() {
    imageEditorHideCrop();
    clearCropMetadata();
    $.fancybox.showActivity();

    $.ajax({
    url: context.url.image + '?page=img-undo' + imageEditorDelimeter
         + 'ImageID=' + context.image_id,
    type: 'POST',
    data: 'zetaprints-action=img-restore' + imageEditorDelimeter
          + 'zetaprints-ImageID=' + context.image_id,
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      alert(zetaprints_trans('Can\'t restore image:') + ' ' + textStatus);
    },
    success: function (data, textStatus) {
      imageEditorApplyImage(data);
      //showImageEditorTooltip('Image Restored');
      //$('#userImagePreview').fadeIn();
    }
    });
  }

  function load_image () {
    $.fancybox.showActivity();

    $.ajax({
      url: context.url.image + '?page=img-props' + imageEditorDelimeter
           + 'ImageID=' + context.image_id,
      type: 'POST',
      datatype: 'XML',
      data: 'zetaprints-action=img&zetaprints-ImageID=' + context.image_id,
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          alert(zetaprints_trans('Can\'t load image:') + ' ' + textStatus);
        },
      success: function (data, textStatus) {
        imageEditorApplyImage(data);
        //showImageEditorTooltip('Image Loaded');
      }
    });
  }

  /**
   * Perform image rotate
   */
  function imageEditorDoRotate(dir) {
    imageEditorHideCrop();
    clearCropMetadata();
    $.fancybox.showActivity();

    $.ajax({
      url: context.url.image + '?page=img-rot' + imageEditorDelimeter
           + 'Rotation=' + dir + imageEditorDelimeter
           + 'ImageID=' + context.image_id,
      type: 'POST',
      data: 'zetaprints-action=img-rotate&zetaprints-Rotation=' + dir
            + '&zetaprints-ImageID=' + context.image_id,
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert(zetaprints_trans('Can\'t rotate image:') + ' ' + textStatus);
      },
      success: function (data, textStatus) {
        imageEditorApplyImage(data);
        //showImageEditorTooltip('Image Rotated');
        //$('#userImagePreview').fadeIn();
      }
    });
  }

  /**
   * Parse XML output and change image
   */
  function imageEditorApplyImage (xml) {
    $.fancybox.showActivity();

    var userImageSrc = context.url.user_image_template
      .replace('image-guid.image-ext', getRegexpValue(xml, /Thumb="([^"]*?)"/));
    var userImageWidthPreview = getRegexpValue(xml, /ThumbWidth="([^"]*?)"/);
    var userImageHeightPreview = getRegexpValue(xml, /ThumbHeight="([^"]*?)"/);
    var userImageWidthActual = getRegexpValue(xml, /ImageWidth="([^"]*?)"/);
    var userImageHeightActual = getRegexpValue(xml, /ImageHeight="([^"]*?)"/);
    var userImageWidthUndo = getRegexpValue(xml, /ImageWidthUndo="([^"]*?)"/);
    var userImageHeightUndo = getRegexpValue(xml, /ImageHeightUndo="([^"]*?)"/);

    var $undo_parent = $('#undo-button').parent();

    if (!userImageHeightUndo || !userImageWidthUndo)
      $undo_parent.addClass('hidden');
    else
      $undo_parent
        .removeClass('hidden')
        .end()
        .attr('title', zetaprints_trans('Undo all changes') + '. '
              + zetaprints_trans('Original size') + ': ' + userImageWidthUndo
              + ' x ' + userImageHeightUndo + ' px.');

    if (!userImageWidthPreview || !userImageHeightPreview) {
      alert(zetaprints_trans('Unknown error occured'));
      return false;
    }

    if (!userImageWidthActual || !userImageHeightActual) {
      alert(zetaprints_trans('Unknown error occured'));
      return false;
    } else {
      _cropVisualAssistant.setUserImage(userImageWidthActual, userImageHeightActual, userImageWidthPreview, userImageHeightPreview);

      set_info_bar_value('current', 'width', userImageWidthActual);
      set_info_bar_value('current', 'height', userImageHeightActual);
    }

    var image_width_in = userImageWidthActual / context.placeholder.width
                                   * _cropVisualAssistant.templateImage.widthIn;

    //console.log('image_width_in', image_width_in);

    image_dpi = Math.round(userImageWidthActual / image_width_in);

    //console.log('image dpi', image_dpi);

    set_info_bar_value('current', 'dpi', image_dpi);

    var width_factor = container_size.width / userImageWidthPreview;
    var height_factor = container_size.height / userImageHeightPreview;

    thumb_to_container_factor
                  = width_factor < height_factor ? width_factor : height_factor;

    $user_image
      .addClass('zetaprints-hidden')
      .attr('src', userImageSrc)
      .css('width', userImageWidthPreview * thumb_to_container_factor)
      .css('height', userImageHeightPreview * thumb_to_container_factor);

    var tmp1 = $('input[value="' + context.image_id + '"]').parent().find('img');
    if (tmp1.length == 0)
      tmp1 = $('#img' + context.image_id);
    if (tmp1.length == 0)
      tmp1 = $('input[value="' + context.image_id + '"]').parent().find('img');
    if (userImageSrc.match(/\.jpg/m))
      tmp1.attr('src', userImageSrc.replace(/\.(jpg|gif|png|jpeg|bmp)/i, "_0x100.jpg"));
    else
      tmp1.attr('src', userImageSrc);
  }

  /**
   * Perform image delete
   */
  function imageEditorDelete() {
    if (confirm(zetaprints_trans('Delete this image?'))){
      $.ajax({
        url: context.url.image + '?page=img-del' + imageEditorDelimeter
             + 'ImageID=' + context.image_id,
        type: 'POST',
        data: 'zetaprints-action=img-delete&zetaprints-ImageID='
              + context.image_id,
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          alert(zetaprints_trans('Can\'t delete image:') + ' ' + textStatus);
        },
        success: function (data, textStatus) {
          clearCropMetadata();
          //remove image from strip and close fancybox
          $('input[value="' + context.image_id +'"]').parent().remove();
          //also try to remove every element with imageEditorId
          $('#' + context.image_id).remove();

          $.fancybox.close();
        }
      });
    }
  }

  function set_info_message (text) {
   $('#info-message').html(zetaprints_trans(text));
  }

  function set_info_bar_value (type, key, value) {
    info_bar_elements[type][key].html(value);
  }

  function set_info_bar_warning (warning) {
    if (warning)
      $info_bar.addClass('warning ' + warning);
    else
      $info_bar.removeClass('warning low-resolution-warning ' +
                            'low-cropped-resolution-warning ' +
                            'low-full-resolution-warning small-image-warning');
  }

  function set_info_bar_state (state, on) {
    if (!state) {
      $info_bar.removeClass('cropped-state');
    }

    if (on)
      $info_bar.addClass(state + '-state');
    else
      $info_bar.removeClass(state + '-state');
  }

  function limit_a_to_b (start_a, length_a, start_b, length_b) {
    if (length_a == 0)
      return 0;

    var end_a = start_a + length_a;
    var end_b = start_b + length_b;

    if (start_a >= end_b || end_a <= start_b)
      return 0;

    if (start_a < start_b)
      start_a = start_b;

    if (end_a > end_b)
      end_a = end_b;

    return end_a - start_a;
  }

  function get_factor_a_to_b (width_a, height_a, width_b, height_b) {
    var width_factor = width_a / width_b;
    var height_factor = height_a / height_b;

    return width_factor < height_factor ? width_factor : height_factor;
  }

  // Check if zetaprints_trans function exists, if not exists create dummy one
  if (!window.zetaprints_trans) {
    function zetaprints_trans (msg) {
      return msg;
    }
  }

  /**
   * Parse regular expression
   */
  function getRegexpValue (subject, exp) {
    match = subject.match(exp);
    if (match != null) {
      if (match.length > 2)
        return match;
      else
        return match[1];
    }
    else
      return false;
  }

  var isCropFit = context.options['in-context']
                   ? context.options['in-context']['@enabled'] == '1' : false;

  //image load handler. Fade in on load, hide loading icon, show image caption
  $user_image.load(function () {
    $user_image.removeClass('zetaprints-hidden')

    if (!isCropFit) {
      _cropVisualAssistant.cropedAreaHide();

      //$('#fancybox-close').click(function() {
      //  _cropVisualAssistant.cropedAreaShow();
      //});

      //$('#imageEditorImageInfo').empty().append(getEditAndSaveInfoBar());
      //updateEditAndSaveInfoBar(userImageWidthPreview, userImageHeightPreview);
    } //else {
      //imageEditorCrop();

      //_cropVisualAssistant.getInfoBar().appendTo($('#imageEditorImageInfo'));
      //_cropVisualAssistant.updateInfoBar(userImageWidthPreview, userImageHeightPreview);
    //}

    imageEditorCrop();

    //$('#userImagePreview').ready(function () {
     $.fancybox.hideActivity();
      //$('#imageEditorInfoBar').show();
    //});

    //imageEditorAdjustSize();
  });

  //button handlers
  $('#crop-button').click(function() {
    imageEditorHideCrop();
    isCropFit = false;

    $info_bar.addClass('hidden');
    $restore_button.addClass('zetaprints-hidden');

    clearCropMetadata();
    imageEditorCrop();
  });

  $('#fit-to-field-button').click(function(){
    imageEditorHideCrop();
    isCropFit = true;

    $info_bar.removeClass('hidden');
    $restore_button.removeClass('zetaprints-hidden');

    imageEditorCrop();
  });

  $('#save-button').click(imageEditorApplyCrop);

  $('#undo-button').click(imageEditorRestore);

  $restore_button.click(function () {
    imageEditorHideCrop();
    clearCropMetadata();
    imageEditorCrop();
  });

  $('#rotate-right-button').click( function () {
    imageEditorDoRotate('r');
  });

  $('#rotate-left-button').click( function () {
    imageEditorDoRotate('l');
  });

  $('#delete-button').click(imageEditorDelete);
}
