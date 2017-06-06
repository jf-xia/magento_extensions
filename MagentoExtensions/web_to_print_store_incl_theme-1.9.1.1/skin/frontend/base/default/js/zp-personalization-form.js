function personalization_form ($) {
  var zp = this;

  function scroll_strip(panel) {
    if ($(panel).hasClass('images-scroller')) {
      $(panel).scrollLeft(0);
      var position = $('input:checked', panel).parents('td').position();
      if (position)
        $(panel).scrollLeft(position.left);
    }
    return true;
  }

  zp.scroll_strip = scroll_strip;

  function show_image_edit_dialog (image_name, src, $thumb) {
    var image_name = unescape(image_name);

    $.fancybox({
      'padding': 0,
      'titleShow': false,
      'type': 'ajax',
      'href': src,
      'hideOnOverlayClick': false,
      'hideOnContentClick': false,
      'centerOnScroll': false,
      'showNavArrows': false,
      'onStart' : function () {
        if ($('#zp-update-preview-button').length
            && window.fancybox_remove_update_preview_button)
          fancybox_remove_update_preview_button($);

        if ($('#fancybox-resize').length && window.fancybox_resizing_hide)
          fancybox_resizing_hide();

      },
      'onComplete': function () {
        zp.image_edit = {
          'url': {
            'image': zp.url.image,
            'user_image_template': zp.url['user-image-template'] },
          '$selected_thumbnail': $thumb,
           //!!! Temp solution
          '$input': $thumb.parents().children('input.zetaprints-images'),
          'image_id': $thumb.attr('id'),
          'page': {
            'width_in': zp.template_details.pages[zp.current_page]['width-in'],
            'height_in': zp.template_details.pages[zp.current_page]['height-in']
          },
          'placeholder': zp.template_details.pages[zp.current_page]
                                                          .images[image_name],
          'shape': zp.template_details.pages[zp.current_page]
                                                          .shapes[image_name],
          'options': zp.options['image-edit']
                       ?  zp.options['image-edit'] : {} };

        zetaprint_image_editor.apply(zp.image_edit, [$]);
      },

      'onClosed': function () {
        var $input = zp.image_edit.$input;

        if (!$input.length)
          return;

        var metadata = $input.data('metadata');

        if (metadata) {
          metadata['img-id'] = $input.attr('value');

          zp_set_metadata(zp.image_edit.placeholder, metadata);
        } else
          zp_clear_metadata(zp.image_edit.placeholder);
      } });
  }

  function export_previews_to_string (template_details) {
    var previews = '';

    for (page_number in template_details.pages)
      if (template_details.pages[page_number]['updated-preview-image'])
        previews += ','
            + template_details.pages[page_number]['updated-preview-image'];

    return previews.substring(1);
  }

  function add_fake_add_to_cart_button ($original_button,
                                        is_multipage_template) {

    var title = $original_button.attr('title')

    if (is_multipage_template)
      var notice = window.notice_to_update_preview_text_for_multipage_template;
    else
      var notice = window.notice_to_update_preview_text;

    var $fake_button_with_notice = $(
        '<button id="zetaprints-fake-add-to-cart-button"' +
                'class="button disable" type="button"' +
                'title="' + title + '">' +
          '<span><span>' + title + '</span></span>' +
        '</button>' +
        '<span id="zetaprints-fake-add-to-cart-warning"' +
              'class="zetaprints-notice to-update-preview">' +
          notice +
        '</span>' );

    $original_button.addClass('no-display').after($fake_button_with_notice);
  }

  function remove_fake_add_to_cart_button ($original_button) {
    $('#zetaprints-fake-add-to-cart-button, ' +
      '#zetaprints-fake-add-to-cart-warning').remove();
    $original_button.removeClass('no-display');
  }

  function can_show_next_page_button_for_page (page, zp) {
    if (page < zp.template_details.pages_number
        && (zp.template_details.pages[page].static
            || zp.changed_pages[page]))
      return true;

    return false;
  }

  var $product_image_box = $('#zetaprints-preview-image-container').css('position', 'relative');
  var product_image_element = $('#image').parent()[0];
  var has_image_zoomer = $(product_image_element).hasClass('product-image-zoom');

  var $add_to_cart_button = $('#zetaprints-add-to-cart-button');

  //If base image is not set
  if (!has_image_zoomer)
    //then remove all original images placed by M.
    $(product_image_element).empty();

  //and it's personalization step (for 2-step theme)
  if (this.is_personalization_step) {
    //remove zoomer and base image
    $(product_image_element).removeClass('product-image-zoom');
    $('#image, #track_hint, div.zoom').remove();
    has_image_zoomer = false;
  }

  //Add placeholders with spinners for preview images to the product page
  for (var page_number in this.template_details.pages)
    $('<div id="zp-placeholder-for-preview-' + page_number +
      '" class="zetaprints-preview-placeholder zp-hidden"><div class=' +
      '"zetaprints-big-spinner" /></div>').appendTo(product_image_element);

  //If no image zoomer on the page
  if (!has_image_zoomer)
    //then show placeholder and spinner for the first page
    $('#zp-placeholder-for-preview-1').removeClass('zp-hidden');

  //Set current template page to the first (1-based index)
  this.current_page = 1;

  //Add TemplateID parameter to the form
  $('<input type="hidden" name="zetaprints-TemplateID" value="' +
    this.template_details.guid +'" />').appendTo('#product_addtocart_form');

  //If update_first_preview_on_load parameter was set
  if (this.update_first_preview_on_load) {
    //Add over-image spinner for the first preview
    $('<div id="zetaprints-first-preview-update-spinner" class="' +
      'zetaprints-big-spinner zetaprints-over-image-spinner zp-hidden" />')
      .appendTo($product_image_box);

    //Update preview for the first page
    update_preview({ data: { zp: this } }, true);
  }

  //Add previews to the product page
  for (var page_number in this.template_details.pages) {
    if (this.previews_from_session)
      var url
            = this.template_details.pages[page_number]['updated-preview-image'];
    else
      var url = this.template_details.pages[page_number]['preview-image'];

    var zp = this;

    $('<a id="preview-image-page-' + page_number +
      '" class="zetaprints-template-preview zp-hidden" href="' + url +
      '"><img title="' + click_to_view_in_large_size + '" src="' + url +
      '" /></a>')
    .children()
    .bind('load', {page_number: page_number}, function (event) {

      //Hide notice about image loading
      $('div.zetaprints-preview-button span.text, ' +
          'div.zetaprints-preview-button img.ajax-loader')
            .css('display', 'none');

      //Show or hide Next page button for the current page
      if (can_show_next_page_button_for_page(zp.current_page, zp))
        $('div.zetaprints-next-page-button').show();
      else
        $('div.zetaprints-next-page-button').hide();

      //Show Update preview button after preview image has been loaded.
      $('button.update-preview').removeClass('zp-hidden');

      //Hide placeholder and spinner after image has loaded
      $('#zp-placeholder-for-preview-' + event.data.page_number)
        .addClass('zp-hidden');

      //If no image zoomer on the page and image is for the first page
      //and first page was opened
      if (!has_image_zoomer && event.data.page_number == 1
          && zp.current_page == 1)
        //then show preview for the first page
        $('#preview-image-page-1').removeClass('zp-hidden');

      //If update_first_preview_on_load parameter was set and
      //first default preview has already been loaded then...
      if (zp.update_first_preview_on_load && event.data.page_number == 1)
        //...show over-image spinner
        $('div#zetaprints-first-preview-update-spinner')
          .removeClass('zp-hidden');
    }).end().appendTo(product_image_element);
  }

  //Iterate over all image fields in template details...
  for (var page in this.template_details.pages)
    for (var name in this.template_details.pages[page].images)
      //... and if image field has a value then...
      if (this.template_details.pages[page].images[name].value)
        //... mark it as EDITED
        $('#stock-images-page-' + page)
          .children('[title="' + name +'"]')
          .removeClass('no-value');

  //Get all dropdown text fields
  $selects = $('.zetaprints-page-input-fields')
               .find('select.zetaprints-field');

  //Iterate over all text fields in template details...
  for (var page in this.template_details.pages)
    for (var name in this.template_details.pages[page].fields)
      //... and if text field has combobox flag then...
      if (this.template_details.pages[page].fields[name].combobox)
        //convert relevant DOM element into a combobox
        $selects
          .filter('[name="zetaprints-_' + name + '"]')
          .wrap('<div class="zetaprints-text-field-wrapper" />')
          .combobox();

  $('#stock-images-page-1, #input-fields-page-1, #page-size-page-1')
    .removeClass('zp-hidden');

  $('div.zetaprints-image-tabs, div.zetaprints-preview-button').css('display', 'block');

  $('div.zetaprints-image-tabs li:first').addClass('selected');

  $('div.tab.user-images').each(function() {
    var tab_button = $('ul.tab-buttons li.hidden', $(this).parents('div.selector-content'));

    if ($('td', this).length > 0)
      $(tab_button).removeClass('hidden');
  });

  //??? Do we need it anymore?
  this.changed_pages = new Array(this.template_details.pages_number + 1);

  if (!this.previews)
    this.previews = [];

  for (var number in this.template_details.pages)
    if (this.template_details.pages[number].static) {
      this.previews[number - 1] = null;
      this.changed_pages[number] = true;
    }

  //Create array for preview images sharing links
  if (window.place_preview_image_sharing_link)
    this.preview_sharing_links
                            = new Array(this.template_details.pages_number + 1);

   $('<input type="hidden" name="zetaprints-previews" value="' +
      export_previews_to_string(this.template_details) + '" />')
      .appendTo($('#product_addtocart_form'));

  if (this.previews_from_session)
    $('div.zetaprints-notice.to-update-preview').addClass('zp-hidden');
  else
    add_fake_add_to_cart_button($add_to_cart_button,
                                this.template_details.pages['2'] != undefined);

  //Add resizer for text inputs and text areas for the first page
  $('#input-fields-page-1 .zetaprints-text-field-wrapper').text_field_resizer();

  $('div.zetaprints-image-tabs li').click({zp: this}, function (event) {
    $('div.zetaprints-image-tabs li').removeClass('selected');

    //Hide preview image, preview placeholder with spinner, text fields
    //and image fields for the current page
    $('a.zetaprints-template-preview, div.zetaprints-page-stock-images, div.zetaprints-page-input-fields, div.zetaprints-preview-placeholder, .page-size-table-body').addClass('zp-hidden');

    //Remove shapes for current page
    if (event.data.zp.has_shapes && window.remove_all_shapes)
      remove_all_shapes($product_image_box);

    $(this).addClass('selected');
    var page = $('img', this).attr('rel');

    //If there's image zoomer on the page
    if (has_image_zoomer) {
      //remove it and base image
      $(product_image_element).removeClass('product-image-zoom');
      $('#image, #track_hint, div.zoom').remove();
      has_image_zoomer = false;
    }

    //Show preview image, preview placeholder with spinner, text fields
    //and image fields for the selected page
    $('#preview-image-' + page + ', #stock-images-' + page + ', #input-fields-'
      + page + ', #zp-placeholder-for-preview-' + page + ', #page-size-'
      + page).removeClass('zp-hidden');

    //Add resizer for text inputs and text areas for the selected page
    $('#input-fields-' + page + ' .zetaprints-text-field-wrapper')
      .text_field_resizer();

    //Remember number of selected page
    event.data.zp.current_page = page.split('-')[1] * 1;

    //Check if page is static then...
    if (event.data.zp.template_details.pages[event.data.zp.current_page].static)
      //... hide Update preview button
      $('button.update-preview').addClass('zp-hidden');
    else
      //... otherwise show it
      $('button.update-preview').removeClass('zp-hidden');

    //Set preview images sharing link for the current page
    if (window.place_preview_image_sharing_link)
      set_preview_sharing_link_for_page(event.data.zp.current_page,
                                        event.data.zp.preview_sharing_links);

    //Add shapes for selected page
    if (event.data.zp.has_shapes
        && window.place_all_shapes_for_page
        && window.shape_handler)
      place_all_shapes_for_page(event.data.zp.template_details.pages[event.data.zp.current_page].shapes, $product_image_box, shape_handler);

    if (can_show_next_page_button_for_page(event.data.zp.current_page,
                                           event.data.zp))
      $('div.zetaprints-next-page-button').show();
    else
      $('div.zetaprints-next-page-button').hide();
  });

  function add_preview_sharing_link_for_page (page_number, links, filename) {
    links[page_number] = preview_image_sharing_link_template + filename;

    $('span.zetaprints-share-link').removeClass('empty');
    $('#zetaprints-share-link-input').val(links[page_number]);
  }

  function set_preview_sharing_link_for_page (page_number, links) {
    if (links[page_number]) {
      $('span.zetaprints-share-link').removeClass('empty');
      $('#zetaprints-share-link-input').val(links[page_number]);
    } else {
      $('span.zetaprints-share-link').addClass('empty');
      $('#zetaprints-share-link-input').val('');
    }
  }

  function prepare_string_for_php (s) {
    return s.replace(/\./g, '\x0A');
  }

  function prepare_post_data_for_php (data) {
    var _data = '';

    data = data.split('&');
    for (var i = 0; i < data.length; i++) {
      var token = data[i].split('=');
      _data += '&' + prepare_string_for_php(token[0]) + '=' + token[1];
    }

    return _data.substring(1);
  }

  function prepare_metadata_from_page (page) {
    var metadata = '';

    for (name in page.images) {
      var field_metadata = zp_convert_metadata_to_string(page.images[name]);

      if (!field_metadata)
        continue;

      metadata += '&zetaprints-*#' + prepare_string_for_php(name) + '='
                  + field_metadata + '&';
    }

    for (var name in page.fields) {
      var field_metadata = zp_convert_metadata_to_string(page.fields[name]);

      if (!field_metadata)
        continue;

      metadata += '&zetaprints-*_' + prepare_string_for_php(name) + '='
                  + field_metadata + '&';
    }

    return metadata;
  }

  function serialize_fields_for_page (page_number) {
    return $('#input-fields-page-' + page_number + ', #stock-images-page-'
                                                                  + page_number)
      .find('.zetaprints-field')
      .filter(':text, textarea, :checked, select, [type="hidden"]')
      .serialize();
  }

  function update_preview (event, preserve_fields) {
    $('div.zetaprints-preview-button span.text, ' +
      'div.zetaprints-preview-button img.ajax-loader').css('display', 'inline');

    var update_preview_button = $('button.update-preview')
                                                         .addClass('zp-hidden');

    $('div.zetaprints-page-input-fields input,' +
      'div.zetaprints-page-input-fields textarea').each(function () {

      $(this).text_field_editor('hide');
    });

    //Convert preserve_field parameter to query parameter
    var preserve_fields = typeof(preserve_fields) != 'undefined'
      && preserve_fields ? '&zetaprints-Preserve=yes' : preserve_fields = '';

    var zp = event.data.zp;

    //!!! Workaround
    //Remember page number
    var current_page = zp.current_page;

    var metadata =
         prepare_metadata_from_page(zp.template_details.pages[zp.current_page]);

    $.ajax({
      url: zp.url.preview,
      type: 'POST',
      dataType: 'json',
      data: prepare_post_data_for_php(serialize_fields_for_page(current_page))
        + '&zetaprints-TemplateID=' + zp.template_details.guid
        + '&zetaprints-From=' + current_page + preserve_fields + metadata,
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        $('div.zetaprints-preview-button span.text, img.ajax-loader').css('display', 'none');
        $(update_preview_button).show();
        alert(preview_generation_response_error_text + textStatus); },
      success: function (data, textStatus) {
        if (!data) {
          alert(preview_generation_error_text);
        } else {
          //!!! Make code in function to not depend on current page number
          //!!! (it's broken way to update preview, user can switch to another
          //!!! page while updating preview)
          //!!! Go throw template details and update previews which has updated
          //!!! preview images (updated-preview-image field)

          //!!! Use updated-preview-image and updated-thumb-image instead
          //!!! updated-preview-url and updated-preview-url
          //!!! Make urls in controller
          //Update links to preview image on current page
          $('#preview-image-page-' + current_page).attr('href',
                              data.pages[current_page]['updated-preview-url']);
          $('#preview-image-page-' + current_page + ' img').attr('src',
                              data.pages[current_page]['updated-preview-url']);

          var preview_filename = data.pages[current_page]['updated-preview-url']
                                   .split('/preview/')[1];

          //Generate preview sharing link if it was enabled
          if (window.place_preview_image_sharing_link)
            add_preview_sharing_link_for_page(current_page,
                                    zp.preview_sharing_links, preview_filename);

          //Update link to preview image in opened fancybox
          var fancy_img = $('#fancybox-img');
          if (fancy_img.length)
            $(fancy_img).attr('src',
                              data.pages[current_page]['updated-preview-url']);

          if (!zp.previews)
            zp.previews = [];

          //Remember file name of preview image for current page
          zp.previews[current_page - 1] = preview_filename;

          //Update link to preview thumbnail for current page tab
          $('div.zetaprints-image-tabs img[rel="page-' + current_page + '"]')
            .attr('src', data.pages[current_page]['updated-thumb-url']);

          //If there's image zoomer on the page
          if (has_image_zoomer) {
            //remove it and base image
            $(product_image_element).removeClass('product-image-zoom');
            $('#image, #track_hint, div.zoom').remove();
            has_image_zoomer = false;
            //and show preview image for the current page
            $('#preview-image-page-' + current_page).removeClass('zp-hidden');

            //Add all shapes to personalization form after first preview
            //update
            if (zp.has_shapes && window.place_all_shapes_for_page
                && window.shape_handler)
              place_all_shapes_for_page(zp.template_details.pages[zp.current_page].shapes,
                                        $product_image_box,
                                        shape_handler);
          }

          if (zp.previews.length == zp.template_details.pages_number) {
            var previews = '';

            for (var i = 0; i < zp.previews.length; i++)
              if (zp.previews[i])
                previews += ',' + zp.previews[i];

            $('input[name="zetaprints-previews"]').val(previews.substring(1));

            $('div.zetaprints-notice.to-update-preview').addClass('zp-hidden');
            remove_fake_add_to_cart_button($add_to_cart_button);
            $('div.save-order span').css('display', 'none');
          }
        }

        zp.changed_pages[current_page] = true;

        //If update_first_preview_on_load parameter was set then...
        if (zp.update_first_preview_on_load)
          //.. remove over-image spinner
          $('div#zetaprints-first-preview-update-spinner').remove();
      }
    });

    return false;
  }

  zp.update_preview = update_preview;

  $('button.update-preview').click({zp: this}, update_preview);

  var upload_controller_url = this.url.upload;
  var image_controller_url = this.url.image;

  $('div.button.choose-file').each(function () {
    var uploader = new AjaxUpload(this, {
      name: 'customer-image',
      action: upload_controller_url,
      autoSubmit: true,
      onChange: function (file, extension) {
        var upload_div = $(this._button).parents('div.upload');
        $('input.file-name', upload_div).val(file);
      },
      onSubmit: function (file, extension) {
        var upload_div = $(this._button).parents('div.upload');
        $('div.button.choose-file', upload_div).addClass('disabled');
        $('div.button.cancel-upload', upload_div).removeClass('disabled');
        $('img.ajax-loader', upload_div).show();

        this.disable();
      },
      onComplete: function (file, response) {
        this.enable();

        var upload_div = $(this._button).parents('div.upload');
        $('div.button.choose-file', upload_div).removeClass('disabled');
        $('div.button.cancel-upload', upload_div).addClass('disabled');
        $('input.file-name', upload_div).val('');

        if (response == 'Error') {
          $('img.ajax-loader', upload_div).hide();
          alert(uploading_image_error_text);
          return;
        }

        var upload_field_id = $(upload_div).parents('div.selector-content').attr('id');

        response = response.split(';');

        var trs = $('div.selector-content div.tab.user-images table tr');

        var number_of_loaded_imgs = 0;

        $(trs).each(function () {
          var image_name = $('input[name="parameter"]', $(this).parents('div.user-images')).val();

          var td = $(
            '<td>\
              <input type="radio" name="zetaprints-#' + image_name + '" value="'
                + response[0] + '" class="zetaprints-images zetaprints-field" />\
              <a class="edit-dialog" href="' + response[1] + 'target="_blank"\
                title="' + click_to_edit_text + '">\
                <img id="' + response[0] + '" src="' + response[2] + '" />\
                <div class="buttons-row">\
                  <div class="button delete" title="' + click_to_delete_text
                    + '" rel="' + response[0] +'">' + delete_button_text +
                  '</div>\
                  <div class="button edit" title="' + click_to_edit_text +
                    '" rel="' + response[0] +'">' + edit_button_text +
                  '</div>\
                </div>\
              </a>\
            </td>').prependTo(this);

          $('input:radio', td).change({ zp: zp }, image_field_select_handler);

          var tr = this;

          $('img', td).load(function() {

            //If a field the image was uploaded into is not current image field
            if ($(this).parents('div.selector-content').attr('id') != upload_field_id) {
              var scroll = $(td).parents('div.images-scroller');

              //Scroll stripper to save position of visible images
              $(scroll).scrollLeft($(scroll).scrollLeft() + $(td).outerWidth());
            }

            var img = this;

            $('a.edit-dialog, div.button.edit', tr).click(function () {
              var $link = $(this);

              //If customer clicks on Edit button then...
              if (this.tagName == 'DIV')
                //... get link to the image edit dialog and use its attributes
                var $link = $(this).parents('td').children('a');

              show_image_edit_dialog(image_name,
                                     $link.attr('href'),
                                     $link.find('img') );

              return false;
            });

            $('.button.delete', td).click(function() {
              if (confirm(delete_this_image_text)) {
                var imageId = $(this).attr('rel');

                $.ajax({
                  url: zp.url.image,
                  type: 'POST',
                  data: 'zetaprints-action=img-delete&zetaprints-ImageID='+imageId,
                  error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(cant_delete_text + ': ' + textStatus);
                  },
                  success: function (data, textStatus) {
                    $('input[value="'+imageId+'"]').parent().remove();
                  }
                });
              }

              return false;
            });

            if (++number_of_loaded_imgs == trs.length) {
              $('div.tab.user-images input[value="' + response[0] + '"]',
                $(upload_div).parent()).attr('checked', 1).change();

              $('img.ajax-loader', upload_div).hide();

              $('div.selector-content')
                .find('ul.tab-buttons li.hidden')
                .removeClass('hidden');

              $(upload_div).parents('div.selector-content').tabs('select', 1);
            }
          });
        });
      }
    });

    $('div.button.cancel-upload', $(this).parent()).click(function () {
      if (!$(this).hasClass('disabled')) {
        uploader.cancel();
        uploader.enable();

        var upload_div = $(uploader._button).parents('div.upload');

        $('img.ajax-loader', upload_div).hide();
        $('div.button.choose-file', upload_div).removeClass('disabled');
        $('div.button.cancel-upload', upload_div).addClass('disabled');
        $('input.file-name', upload_div).val('');
      }
    });
  })

  function image_field_select_handler (event) {
    var $selector = $(event.target).parents('div.zetaprints-images-selector');
    var $content = $selector.parents('.selector-content');

    if (!$selector.get(0)) {
      $content =  $(event.target).parents('.selector-content');
      $selector = $content.data('in-preview-edit').parent;
    }

    var zp = event.data.zp;

    if ($(event.target).val().length) {
      $selector.removeClass('no-value');

      $('#fancybox-outer').addClass('modified');

      //If ZetaPrints advanced theme is enabled then...
      if (window.mark_shape_as_edited)
        //... mark shape as edited then image is seleсted
        mark_shape_as_edited(zp.template_details.pages[zp.current_page]
                           .shapes[$(event.target).attr('name').substring(12)]);
    } else {
      $selector.addClass('no-value');

      $('#fancybox-outer').removeClass('modified');

      //If ZetaPrints advanced theme is enabled then...
      if (window.unmark_shape_as_edited)
        //or unmark shape then Leave blank is selected
        unmark_shape_as_edited(zp.template_details.pages[zp.current_page]
                           .shapes[$(event.target).attr('name').substring(12)]);
    }
  }

  zp.show_user_images = function ($panel)  {
    if ($panel.find('input.zetaprints-images').length > 0)
      $panel.tabs('select', 1);
  }

  zp.show_colorpicker = function ($panel) {
    if ($panel.hasClass('color-picker')
        && !$panel.find('input').attr('checked'))
      $panel.find('.color-sample').click();
  }

  function has_changed_fields_on_page (page_number) {
    var $fields = $('#input-fields-page-' + page_number + ', ' +
                   '#stock-images-page-' + page_number);

    if (!$fields.length)
      return true;

    var has_value = false;

    $fields = $fields
                .find('*[name^="zetaprints-_"], *[name^="zetaprints-#"]')
                .filter('textarea, select, :text, :checked')
                .filter('*[type!=hidden]');

    if (!$fields.length)
      return false;

    for (var i = 0; i < $fields.length; i++)
      if ($($fields[i]).val())
        return true;

    return false;
  }

  $(window).load({ zp: this }, function (event) {
    var zp = event.data.zp;

    if (zp.has_shapes
        && window.precalculate_shapes
        && window.place_all_shapes_for_page && shape_handler) {

      precalculate_shapes(zp.template_details);

      //Add all shapes only then there's no base image.
      //Shapes will be added after first preview update then base image exists
      if (!has_image_zoomer)
        place_all_shapes_for_page(zp.template_details.pages[zp.current_page].shapes,
                                  $product_image_box,
                                  shape_handler);
    }

    $('.zetaprints-images-selector').each(function () {
      var $field = $(this);

      var $head = $field.children('.head');
      var $content = $field.children('.selector-content');

      var $tabs = $content.children('.tab-buttons');

      var tab_number = 0

      if (!$tabs.children('.hidden').length)
        tab_number = 1;

      $content
        .tabs({ selected: tab_number })
        .bind('tabsshow', function (event, ui) {
          zp.show_colorpicker($(ui.panel));
          scroll_strip(ui.panel);
        });

      $content
        .find('.zetaprints-field')
        .change({ zp: zp }, image_field_select_handler);

      var $panels = $content.find('> .tabs-wrapper > .tab');

      $head.click(function () {
        if ($field.hasClass('minimized')) {
          $field.removeClass('minimized');

          $panel = $panels.not('.ui-tabs-hide');

          zp.show_colorpicker($panel);
          scroll_strip($panel)
        }
        else
          $field
            .addClass('minimized')
            .removeClass('expanded')
            .css('width', '100%');

        return false;
      });

      var shift =
              $field.position().left - $('div.product-img-box').position().left;

      var full_width = shift + $field.outerWidth();

      $head
        .children('.collapse-expand')
        .click(function () {
          $panel = $panels.not('.ui-tabs-hide');

          if ($field.hasClass('expanded'))
            $field
              .removeClass('expanded')
              .removeAttr('style');
          else {
            $field
              .addClass('expanded')
              .css({ 'left': -shift, 'width': full_width });

            if ($field.hasClass('minimized')) {
              $field.removeClass('minimized');

              zp.show_colorpicker($panel);
            }
          }

          scroll_strip($panel);

          return false;
        });

      var $colour_picker_panel = $panels.filter('.color-picker');

      if (!$colour_picker_panel.length)
        return;

      var $colour_radio_button = $colour_picker_panel
                                   .children('.zetaprints-field');

      var $colour_sample = $colour_picker_panel.children('.color-sample')

      var colour = $colour_radio_button.val();

      if (colour)
        $colour_sample.css('backgroundColor', colour);

      $colour_picker_panel
        .find('span > a')
        .click(function () {
          $colour_sample.click();

          return false;
        });

      $colour_sample.ColorPicker({
        color: '#804080',
        onBeforeShow: function (picker) {
          var colour = $colour_radio_button.val();

          if (colour)
            $(this).ColorPickerSetColor(colour);

          $(picker).draggable();
        },
        onSubmit: function (hsb, hex, rgb, picker) {
          $field.removeClass('no-value');
          $colour_sample.css('backgroundColor', '#' + hex);

          $colour_radio_button
            .attr('disabled', 0)
            .val('#' + hex)
            .change()
            .attr('checked', 1);

          $(picker).ColorPickerHide();
        }
      });
    });
  });

  $('div.zetaprints-next-page-button').click({zp: this}, function (event) {
    var next_page_number = event.data.zp.current_page + 1;

    $('div.zetaprints-image-tabs li img[rel="page-' + next_page_number +'"]')
      .parent()
      .click();

    return false;
  });

  $('a.zetaprints-template-preview').fancybox({
    'opacity': true,
    'overlayShow': false,
    'transitionIn': 'elastic',
    'speedIn': 500,
    'speedOut' : 500,
    'titleShow': false,
    'hideOnContentClick': true,
    'showNavArrows': false,
    'onStart' : function () {
      if ($('#zp-select-image-button').length
          && window.fancybox_remove_use_image_button)
        fancybox_remove_use_image_button($);

      if (window.fancybox_add_update_preview_button
          && !zp.template_details.pages[zp.current_page].static) {
        fancybox_add_update_preview_button($, zp);
      }
    },
    'onComplete': function () {
      $('img#fancybox-img').attr('title', click_to_close_text);

      //!!! Needs to be implemented via zp object.
      //!!! Page state should be saved in page object.
      if (has_changed_fields_on_page(zp.current_page))
        $('#fancybox-outer').addClass('modified');
      else
        $('#fancybox-outer').removeClass('modified');

      if (window.fancybox_resizing_add)
        fancybox_resizing_add(this);

      if (!(zp.has_shapes && window.place_all_shapes_for_page
        && window.highlight_shape && window.popup_field_by_name
        && window.fancy_shape_handler))
        return;

      var $fancy_inner = $('div#fancybox-content');

      place_all_shapes_for_page(zp.template_details.pages[zp.current_page].shapes,
                                $fancy_inner, fancy_shape_handler);

      var $current_shape = jQuery('#current-shape');

      if ($current_shape.length) {
        var current_shape_name = $current_shape.attr('title');

        var shape = zp.template_details
                      .pages[zp.current_page]
                      .shapes[current_shape_name];

        highlight_shape(shape, $fancy_inner);

        var $selected_shapes = $product_image_box
                                 .find('.zetaprints-shape-selected')

        var $selected_shapes_array = $selected_shapes
                                       .toArray()
                                       .reverse();

        var selected_shapes_names = [];

        for (var i = 0; i < $selected_shapes_array.length; i++) {
          var names = $($selected_shapes_array[i]).attr('title').split('; ');

          for (var n = 0; n < names.length; n++)
            selected_shapes_names.push(names[n]);
        }

        popup_field_by_name(current_shape_name,
                            undefined,
                            selected_shapes_names);

        $selected_shapes.removeClass('zetaprints-shape-selected');
      }

      zp.current_field_name = null;
    },
    'onCleanup': function () {
      if (window.fancybox_resizing_hide)
        fancybox_resizing_hide();

      if (zp.has_shapes && window.popdown_field_by_name) {
        $('div.zetaprints-field-shape', $('div#fancybox-content')).removeClass('highlighted');
        popdown_field_by_name(undefined, true);
      }
    },
    'onClosed': function () {
      if (window.fancybox_remove_update_preview_button)
        fancybox_remove_update_preview_button($);
    }
    });

  $('a.in-dialog').fancybox({
    'opacity': true,
    'overlayShow': false,
    'transitionIn': 'elastic',
    'changeSpeed': 200,
    'speedIn': 500,
    'speedOut' : 500,
    'titleShow': false,
    'onStart' : function () {
      var is_in_preview = false;

      if ($('#zp-update-preview-button').length
          && window.fancybox_remove_update_preview_button) {
        fancybox_remove_update_preview_button($);

        is_in_preview = true;
      }

      if ($('#fancybox-resize').length && window.fancybox_resizing_hide)
        fancybox_resizing_hide();

      if (window.fancybox_add_use_image_button)
        fancybox_add_use_image_button($, zp, is_in_preview);
    },
    'onComplete': function () {
      if (window.fancybox_update_preview_button)
        fancybox_update_preview_button($);
    },
    'onClosed': function () {
      if (window.fancybox_remove_use_image_button)
        fancybox_remove_use_image_button($);
    }
  });

  $('a.edit-dialog, div.button.edit').click(function() {
    var link = this;

    //If customer clicks on Edit button then...
    if (this.tagName == 'DIV')
      //... get link to the image edit dialog and use its attributes
      var link = $(this).parents('td').children('a');

    show_image_edit_dialog($(link).attr('name'),
                           $(link).attr('href'),
                           $(link).children('img') );

    return false; });

  $('.zetaprints-page-input-fields .zetaprints-field')
    .filter(':input:not([type="hidden"])')
    .each(function () {
      var $text_field = $(this);
      var page = $text_field.parents('.zetaprints-page-input-fields')
                   .attr('id')
                   .substring(18);

      var field = zp.template_details.pages[page]
                    .fields[$text_field.attr('name').substring(12)];

      var cached_value = zp_get_metadata(field, 'col-f', '');

      //Remove metadata values, so they won't be used in update preview requests
      //by default
      zp_set_metadata(field, 'col-f', undefined);

      if (field['colour-picker'] != 'RGB')
        return;

      var $button_container = $text_field.parents('dl').children('dt');

      $text_field.text_field_editor({
        button_parent: $button_container,
        colour: cached_value,

        change: function (data) {
          var metadata = {
            'col-f': data.color }

          zp_set_metadata(field, metadata);
        }
      });
    });

  $('div.zetaprints-page-input-fields input[title], div.zetaprints-page-input-fields textarea[title]').qtip({
    position: { corner: { target: 'bottomLeft' } },
        show: { delay: 1, solo: true, when: { event: 'focus' } },
        hide: { when: { event: 'unfocus' } }
  });

  $('div.zetaprints-page-stock-images select[title]').qtip({
    position: { corner: { target: 'topLeft' }, adjust: { y: -30 } },
        show: { delay: 1, solo: true, when: { event: 'focus' } },
        hide: { when: { event: 'unfocus' } }
  });

  $('div.zetaprints-page-input-fields input.input-text').keypress(function (event) {
    if (event.keyCode == 13)
      return false;
  });

  function text_fields_change_handle (event) {
    var zp = event.data.zp;

    var $target = $(this);

    if ($target.is(':checkbox'))
      var state = $target.is(':checked');
    else
      var state = $(this).val() != '';

    if (state)
      $('#fancybox-outer').addClass('modified');
    else
      $('#fancybox-outer').removeClass('modified');

    if (zp.has_shapes
        && window.mark_shape_as_edited
        && window.unmark_shape_as_edited) {

      var shape = get_shape_by_name($target.attr('name').substring(12),
                             zp.template_details.pages[zp.current_page].shapes);

      if (!shape)
        return;

      if (state)
        mark_shape_as_edited(shape);
      else {
        var names = shape.name.split('; ');

        if (names.length != 1) {
          $text_fields = $('#input-fields-page-' + zp.current_page)
                      .find('input, textarea, select')
                      .filter('textarea, select, :text, :checked');

          $image_fields = $('#stock-images-page-' + zp.current_page)
                            .find('input')
                            .filter(':checked');

          for (var i = 0; i < names.length; i++) {
            var name = names[i];

            if ($text_fields.filter('[name="zetaprints-_' + name +'"]').val() ||
                $image_fields.filter('[name="zetaprints-#' + name +'"]').length)
              return;
          }
        }

        unmark_shape_as_edited(shape);
      }
    }
  }

  function readonly_fields_click_handle (event) {
    $(this)
      .unbind(event)
      .val('')
      .removeAttr('readonly');

    //Workaround for IE browser.
    //It moves cursor to the end of input field after focus.
    if (this.createTextRange) {
      var range = this.createTextRange();

      range.collapse(true);
      range.move('character', 0);
      range.select();
    }
  }

  $('div.zetaprints-page-input-fields')
    .find('.zetaprints-field')
    .filter('textarea, :text')
      .keyup({ zp: this }, text_fields_change_handle)
      .filter('[readonly]')
        .click(readonly_fields_click_handle)
      .end()
    .end()
    .filter('select, :checkbox')
      .change({ zp: this }, text_fields_change_handle);

  $('.button.delete').click({ zp: this }, function(event) {
    if (confirm(delete_this_image_text)) {
      var imageId = $(this).attr('rel');

      $.ajax({
        url: event.data.zp.url.image,
        type: 'POST',
        data: 'zetaprints-action=img-delete&zetaprints-ImageID='+imageId,
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          alert(cant_delete_text + ': ' + textStatus);
        },
        success: function (data, textStatus) {
          $('input[value="'+imageId+'"]').parent().remove();
        }
      });
    }

    return false;
  });

  $('input.zetaprints-images').click({ zp : this }, function (event) {
    var $input = $(this);
    var field = event.data.zp.template_details
                  .pages[event.data.zp.current_page]
                  .images[$input.attr('name').substring(12)];

    var metadata = $input.data('metadata');

    if (metadata) {
      metadata['img-id'] = $input.attr('value');

      zp_set_metadata(field, metadata);
    } else
      zp_clear_metadata(field);
  });

  if (this.has_shapes && window.add_in_preview_edit_handlers)
    add_in_preview_edit_handlers();
}
