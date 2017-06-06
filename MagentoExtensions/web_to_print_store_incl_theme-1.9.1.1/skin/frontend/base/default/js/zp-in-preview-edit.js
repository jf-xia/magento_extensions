function precalculate_shapes (template_details) {
  for (var page in template_details.pages)
    for (var name in template_details.pages[page].shapes) {
      var shape = template_details.pages[page].shapes[name];

      shape.left = shape.x1 * 100;
      shape.top = shape.y1 * 100;
      shape.width = (shape.x2 - shape.x1) * 100;
      shape.height = (shape.y2 - shape.y1) * 100;
    }
}

function place_shape (shape, $container, shape_handler) {
  if (shape['has-value'])
    var edited_class = ' edited';
  else
    var edited_class = '';

  jQuery('<div class="zetaprints-field-shape bottom hide' + edited_class + '"' +
              'title="' + shape.name  + '">' +
           '<div class="zetaprints-field-shape top" />' +
         '</div>')
    .css({
      top: shape.top + '%',
      left: shape.left + '%',
      width: shape.width + '%',
      height: shape.height + '%' })
    .appendTo($container)
    .children()
    .bind('click mouseover mouseout', { container: $container }, shape_handler);
}

function place_all_shapes_for_page (shapes, $container, shape_handler) {
  if (!shapes)
    return;

  for (name in shapes)
    if (!shapes[name].hidden)
      place_shape(shapes[name], $container, shape_handler);
}

function remove_all_shapes (container) {
  jQuery('div.zetaprints-field-shape', container).remove();
}

function highlight_shape (shape, $container) {
  $container
    .find('.zetaprints-field-shape[title="' + shape.name +'"]')
    .addClass('highlighted');
}

function dehighlight_shape (shape, $container) {
  $container
    .find('.zetaprints-field-shape[title="' + shape.name +'"]')
    .removeClass('highlighted');
}

function highlight_field_by_name (names) {
  names = names.split('; ');

  for (var i = 0; i < names.length; i++) {
    var name = names[i];

    var $field = jQuery('*[name="zetaprints-_'+ name +'"], ' +
                      'div.zetaprints-images-selector[title="' + name + '"] div.head');

    var $parent = $field.parents('.zetaprints-text-field-wrapper');

    if ($parent.length)
      $field = $parent;

    $field.addClass('highlighted');
  }
}

function dehighlight_field_by_name (name) {
  jQuery('.zetaprints-page-input-fields .highlighted,' +
         '.zetaprints-page-stock-images .highlighted')
    .removeClass('highlighted');
}

function popup_field_by_name (name, position, selected_shapes) {
  var $tabs = jQuery('<div class="fieldbox-tabs fieldbox-wrapper">' +
                      '<a class="fieldbox-button" href="#" />' +
                      '<ul class="fieldbox-head"/>' +
                    '</div>');

  var $ul = $tabs.children('ul');

  var $shape = jQuery('#fancybox-content')
                 .find('.zetaprints-field-shape[title="' + name + '"]');

  var page = zp.template_details.pages[zp.current_page];

  var width = 'auto'
  var min_width = $shape.outerWidth();

  if (min_width <= 150)
    min_width = 150;

  var selected_buttons = {};

  for (var i = 0; i < selected_shapes.length; i++) {
    var shape_name = selected_shapes[i];

    var tab_title = shape_name;

    if (shape_name.length > 5)
      tab_title = shape_name.substring(0, 5) + '&hellip;';

    var $li = jQuery('<li title="' + shape_name + '">' +
                       '<div class="fieldbox-tab-inner">' +
                         '<a href="#fieldbox-tab-' + i + '">' +
                           '<div class="fieldbox-tab-icon" />' +
                           tab_title +
                         '</a>' +
                         '<div class="zp-clear" />' +
                        '</div>' +
                     '</li>')
                .appendTo($ul);

    if (page.fields && page.fields[shape_name]) {
      var $field = jQuery('#input-fields-page-' + zp.current_page)
                     .find('*[name="zetaprints-_'+ shape_name +'"]')
                     .not(':hidden');

      var $_field = $field;
      var $parent = $field.parents('.zetaprints-text-field-wrapper');

      if ($parent.length)
        $field = $parent;

      var full_name = 'zetaprints-_'+ name;

      if (page.fields[shape_name]['colour-picker'] == 'RGB')
        $_field.text_field_editor('move', $li.find('.fieldbox-tab-inner'));

      $li.addClass('text-field');

      //var field = $field[0];

      //if ($_field) {
        //Workaround for IE browser.
        //It moves cursor to the end of input field after focus.
      //  if (field.createTextRange) {
      //    var range = field.createTextRange();
      //    var position = jQuery(field).val().length;

      //    range.collapse(true);
      //    range.move('character', position);
      //    range.select();
      //  }
      //}
    }
    else if (page.images && page.images[shape_name]) {
      var $parent = jQuery('#stock-images-page-' + zp.current_page)
                     .find('*[title="' + shape_name + '"]')
                     .removeClass('minimized');

      var $field = $parent.children('.selector-content');

      if (min_width < 400)
        width = 400;
      else
        width = min_width;

      //Remember checked radio button for IE7 workaround
      selected_buttons[shape_name] = $field
                                       .find(':checked')
                                       .val();

      var full_name = 'zetaprints-#' + name;

      $li.addClass('image-field');
    }

    $field
      .data('in-preview-edit', { 'style': $field.attr('style'),
                                 'parent': $field.parent() })
      .detach()
      .removeAttr('style')
      .wrap('<div id="fieldbox-tab-' + i + '" class="fieldbox-field" />')
      .parent()
      .appendTo($tabs);
  }

  $ul.append('<div class="last" />');

  $shape
    .append('<input type="hidden" name="field" value="' + full_name + '" />');

  //Oh God, it's a sad story :-(
  if (width == 'auto' && jQuery.browser.msie && jQuery.browser.version == '7.0')
    width = min_width;

  var $box = jQuery('<div class="fieldbox" title="' + name + '" />')
               .append($tabs)
               .css({ width: width,
                      minWidth: min_width })
               .appendTo('body');

  //!!! Stupid work around for stupid IE7
  for (var name in selected_buttons) {
    var id = $ul
               .children('[title="' + name + '"]')
               .find(' > .fieldbox-tab-inner > a')
               .attr('href')
               //IE7 returns full URL
               .split('#');

    $tabs
      .find(' > #' + id[1] + ' > .selector-content')
      .find('input[value="' + selected_buttons[name] + '"]')
      .change()
      .attr('checked', 1);
  }

  $box.find('.fieldbox-button').click(function () {
    popdown_field_by_name();

    return false;
  });

  var height = $box.outerHeight();
  var width = $box.outerWidth();

  if (!position) {
    position = $shape.offset();
    position.top += $shape.outerHeight() - 10;
    position.left += 10;
  }

  var window_height = jQuery(window).height() + jQuery(window).scrollTop();
  if ((position.top + height) > window_height)
    position.top -= position.top + height - window_height;

  var window_width = jQuery(window).width();
  if ((position.left + width) > window_width)
    position.left -= position.left + width - window_width;

  $box.css({
    visibility: 'visible',
    left: position.left,
    top: position.top }).draggable({ handle: '.fieldbox-head' });

  $tabs.tabs({
    show: function (event, ui) {
      $panel = jQuery(ui.panel);

      var $panel = $panel.find($panel
                                 .find('ul.tab-buttons li.ui-tabs-selected a')
                                 .attr('href') );

      if (!$panel.length)
        return;

      zp.show_user_images($panel);
      zp.scroll_strip($panel);
      zp.show_colorpicker($panel);
    }
  });
}

function popdown_field_by_name (full_name) {
  if (full_name)
    var field = jQuery('*[value="'+ full_name +'"]', jQuery('div#fancybox-content'));
  else
    var field = jQuery(':input', jQuery('div#fancybox-content'));

  if (!field.length)
    return;

  if (!full_name)
    full_name = jQuery(field).attr('value');

  var name = full_name.substring(12);

  var $box = jQuery('.fieldbox[title="' + name + '"]');

  $box.find('.fieldbox-field').children().each(function () {
    var $element = jQuery(this);
    var $_element = $element;

    if ($element.hasClass('zetaprints-text-field-wrapper'))
      $_element = $element.find('.zetaprints-field');

    var data = $element.data('in-preview-edit');

    //Remember checked radio button for IE7 workaround
    var $input = $element.find(':checked');

    //!!! Following code checks back initially selected radio button
    //!!! Don't know why it happens
    $element
      .detach()
      .appendTo(data.parent);

    if (data.style == undefined)
      $element.removeAttr('style');
    else
      $element.attr('style', data.style);

    //!!! Stupid work around for stupid IE7
    $input.change().attr('checked', 1);

    $_element.text_field_editor('move',
                               data.parent.parents('dl').children('dt'));

    if (data.parent.hasClass('zetaprints-images-selector'))
      zp.scroll_strip(jQuery($element
                              .find('ul.tab-buttons li.ui-tabs-selected a')
                              .attr('href')) );
  });

  $box.remove();

  jQuery(field).remove();

  jQuery('#current-shape').attr('id', '');

  return name;
}

function mark_shape_as_edited (shape) {
  jQuery('div.zetaprints-field-shape[title="' + shape.name + '"]')
    .addClass('edited');

  shape['has-value'] = true;
}

function unmark_shape_as_edited (shape) {
  jQuery('div.zetaprints-field-shape[title="' + shape.name + '"]').removeClass('edited');

  shape['has-value'] = false;
}

function get_current_shapes_container () {
  var container = jQuery('div#fancybox-content:visible');
  if (container.length)
    return container;

  return jQuery('div.product-img-box');
}

function _glob_to_rel_coords (x, y, $container) {
  var container_offset = $container.offset();

  x = x - container_offset.left;
  y = y - container_offset.top;

  var width = $container.width();
  var height = $container.height();

  return { x: x / width, y: y / height };
}

function get_shapes_by_coords (c) {
  var page = zp.template_details.pages[zp.current_page];

  var shapes = [];

  for (var name in page.shapes) {
    var shape = page.shapes[name];

    if (shape.x1 <= c.x && c.x <= shape.x2
        && shape.y1 <= c.y && c.y <= shape.y2)
      shapes.push(shape);
  }

  return shapes;
}

function shape_handler (event) {
  var shape = jQuery(event.target).parent();

  if (event.type == 'click') {
    if (event.pageX && event.pageY) {
      var c = _glob_to_rel_coords(event.pageX, event.pageY, event.data.container);
      var shapes = get_shapes_by_coords(c);

      //Remember selected shapes for futher use
      shape.data('selected-shapes', shapes);
    } else {
      var shapes = shape.data('selected-shapes');
      shape.data('selected-shapes', undefined);
    }

    for (var i = 0; i < shapes.length; i++)
      event.data.container
        .find('.zetaprints-field-shape.bottom[title="' + shapes[i].name  + '"]')
        .addClass('zetaprints-shape-selected');

    jQuery('#current-shape').attr('id', '');
    jQuery(shape).attr('id', 'current-shape');

    jQuery('#preview-image-page-' + zp.current_page).click();
  } else if (event.type == 'mouseover') {
    jQuery('#zetaprints-preview-image-container > div.zetaprints-field-shape.bottom')
      .removeClass('highlighted');
    jQuery(shape).addClass('highlighted');

      highlight_field_by_name (jQuery(shape).attr('title'));
    } else {
      jQuery(shape).removeClass('highlighted');

      dehighlight_field_by_name (jQuery(shape).attr('title'));
    }

  return false;
}

function fancy_shape_handler (event) {
  var shape = jQuery(event.target).parent();

  if (event.type == 'click') {
    if (jQuery(shape).children().length > 1)
      return false;

    jQuery('div#fancybox-content div.zetaprints-field-shape.highlighted')
      .removeClass('highlighted');

    shape.addClass("highlighted");

    popdown_field_by_name(undefined, true);

    var c = _glob_to_rel_coords(event.pageX,
                                event.pageY,
                                event.data.container.children('#fancybox-img'));

    var selected_shapes = get_shapes_by_coords(c)
                            .reverse();

    //Remember selected shapes for futher use
    jQuery('#zetaprints-preview-image-container')
      .children('.zetaprints-field-shape[title="' + shape.attr('title') + '"]')
      .data('selected-shapes', selected_shapes);

    var selected_shapes_names = [];

    for (var i = 0; i < selected_shapes.length; i++) {
      var names = selected_shapes[i].name.split('; ');

      for (var n = 0; n < names.length; n++)
        selected_shapes_names.push(names[n]);
    }

    popup_field_by_name(jQuery(shape).attr('title'),
                        { top: event.pageY, left: event.pageX },
                        selected_shapes_names);

    return false;
  }

  if (event.type == 'mouseover') {
    var highlighted = jQuery('div#fancybox-content > div.zetaprints-field-shape.highlighted');
    if (jQuery(highlighted).children().length <= 1)
      jQuery(highlighted).removeClass('highlighted');

    jQuery(shape).addClass('highlighted');
  } else
    if (jQuery(shape).children().length <= 1)
      jQuery(shape).removeClass('highlighted');
}

function add_in_preview_edit_handlers () {
  jQuery('div.zetaprints-page-input-fields')
    .find('dd')
    .find('input, textarea, select')
    .mouseover(function() {
      var shapes = zp.template_details
                     .pages[zp.current_page]
                     .shapes;

      var name = jQuery(this).attr('name').substring(12);

      var shape = get_shape_by_name(name, shapes);

      highlight_shape(shape, get_current_shapes_container());
    })
    .mouseout(function() {
      var shapes = zp.template_details
                     .pages[zp.current_page]
                     .shapes;

      var name = jQuery(this).attr('name').substring(12);

      var shape = get_shape_by_name(name, shapes);

      dehighlight_shape(shape, get_current_shapes_container());
    });

  jQuery('div.zetaprints-images-selector').mouseover(function () {
    var shapes = zp.template_details
                   .pages[zp.current_page]
                   .shapes;

    var name = jQuery(this).attr('title');

    var shape = get_shape_by_name(name, shapes);

    highlight_shape(shape, get_current_shapes_container());
  }).mouseout(function () {
    if (!jQuery(this).children('div.fieldbox').length) {
      var shapes = zp.template_details
                     .pages[zp.current_page]
                     .shapes;

      var name = jQuery(this).attr('title');

      var shape = get_shape_by_name(name, shapes);

      dehighlight_shape(shape, get_current_shapes_container());
    }
  });

  jQuery('img#fancybox-img').live('click', function () {
    jQuery('div.zetaprints-field-shape.bottom', jQuery('div#fancybox-content')).removeClass('highlighted');

    popdown_field_by_name(undefined, true);
  });

  var fancybox_center_function = jQuery.fancybox.center;
  jQuery.fancybox.center = function () {
    var orig_position = jQuery('div#fancybox-wrap').position();

    fancybox_center_function();

    var new_position = jQuery('div#fancybox-wrap').position();

    if (orig_position.top != new_position.top
      || orig_position.left != new_position.left)
      popup_field_by_name(popdown_field_by_name());
  }
}

function get_shape_by_name (name, shapes) {
  for (var _name in shapes) {
    var names = _name.split('; ');

    for (var i = 0; i < names.length; i++)
      if (names[i] == name)
        return shapes[_name];
  }

  return null;
}
