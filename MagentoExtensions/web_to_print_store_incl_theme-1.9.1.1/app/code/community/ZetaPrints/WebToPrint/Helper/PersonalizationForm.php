<?php

class ZetaPrints_WebToPrint_Helper_PersonalizationForm
  extends ZetaPrints_WebToPrint_Helper_Data
  implements ZetaPrints_Api {

  private function get_template_guid_from_product ($product) {

    //Get template GUID from webtoprint_template attribute if such attribute exists
    //and contains value, otherwise use product SKU as template GUID
    if (!($product->hasWebtoprintTemplate() && $template_guid = $product->getWebtoprintTemplate()))
      $template_guid = $product->getSku();

    if (strlen($template_guid) != 36)
      return false;

    return $template_guid;
  }

  public function get_template_id ($product) {
    if ($template_guid = $this->get_template_guid_from_product ($product))
      return Mage::getModel('webtoprint/template')->getResource()->getIdByGuid($template_guid);
  }

  private function get_form_part_html ($form_part = null, $product, $params = array()) {
    $template_guid = $this->get_template_guid_from_product($product);

    if (!$template_guid)
      return false;

    //$template = Mage::getModel('webtoprint/template')->load($template_guid);

    //if (!$template->getId())
    //  return false;

    if (! $xml = Mage::registry('webtoprint-template-xml')) {
      //This flag shows a status of web-to-print user registration
      $user_was_registered = true;

      //Check a status of web-to-print user registration on ZetaPrints
      //and if it's not then set user_was_registered flag to false
      if (!($user_credentials = $this->get_zetaprints_credentials())) {
        $template = Mage::getModel('webtoprint/template')->load($template_guid);

        if ($template->getId())
          $user_was_registered = false;
      }

      //Remember a status of web-to-print user registrarion for subsequent
      //function calls
      Mage::register('webtoprint-user-was-registered', $user_was_registered);

      if ($user_was_registered) {
        $url = Mage::getStoreConfig('webtoprint/settings/url');
        $key = Mage::getStoreConfig('webtoprint/settings/key');

        $data = array(
          'ID' => $user_credentials['id'],
          'Hash' => zetaprints_generate_user_password_hash(
                                              $user_credentials['password']) );

        $template_xml = zetaprints_get_template_details_as_xml($url, $key,
                                                        $template_guid, $data);
      } else
        $template_xml = $template->getXml();

      try {
        $xml = new SimpleXMLElement($template_xml);
      } catch (Exception $e) {
        Mage::log("Exception: {$e->getMessage()}");

        return false;
      }

      //If product page was requested with reorder parameter...
      if ($this->_getRequest()->has('reorder')
          && strlen($this->_getRequest()->getParam('reorder')) == 36)
        //...then replace field values from order details
        $this->replace_user_input_from_order_details($xml,
                                    $this->_getRequest()->getParam('reorder'));

      //If product page was requested with for-item parameter...
      if ($this->_getRequest()->has('for-item'))
        //...then replace various template values from item's options
        $this->replace_template_values_from_cart_item($xml,
                                    $this->_getRequest()->getParam('for-item'));

      Mage::register('webtoprint-template-xml', $xml);
    }

    //if ($form_part === 'input-fields' || $form_part === 'stock-images')
    //  $this->add_values_from_cache($xml);

    if ($form_part === 'stock-images'
        && Mage::registry('webtoprint-user-was-registered'))
      $this->add_user_images($xml);

    if ($form_part === 'page-tabs') {
      $this->update_preview_images_urls($xml);

      $session = Mage::getSingleton('core/session');

      if ($session->hasData('zetaprints-previews')) {
        $previews = unserialize($session->getData('zetaprints-previews'));

        if (is_array($previews))
          $this->replace_preview_images($xml, $previews);
      }
    }

    $params = array_merge(
      $params,
      array('zetaprints-api-url'
                      => Mage::getStoreConfig('webtoprint/settings/url') . '/' )
    );

    //Append translations to xml
    $locale_file = Mage::getBaseDir('locale') . DS
                   . Mage::app()->getLocale()->getLocaleCode() .DS
                   .'ZetaPrints_WebToPrint.csv';

    $custom_translations_file = Mage::getBaseDir('locale') . DS
                                . Mage::app()->getLocale()->getLocaleCode() . DS
                                . 'ZetaPrints_WebToPrintCustomTranslations.csv';

    if (file_exists($locale_file) || file_exists($custom_translations_file)) {
      $cache = Mage::getSingleton('core/cache');
      $out = $cache->load("XMLTranslation".Mage::app()->getLocale()->getLocaleCode());

      if (strlen($out) == 0) {
        $locale = @file_get_contents($locale_file)
                  . @file_get_contents($custom_translations_file);

        preg_match_all('/"(.*?)","(.*?)"(:?\r|\n|$)/', $locale, $array, PREG_PATTERN_ORDER);

        if (is_array($array) && count($array[1]) > 0) {
          $out = '<trans>';

          foreach ($array[1] as $key => $value) {
            if (strlen($value) > 0 && strlen($array[2][$key]) > 0) {
              $out .= "<phrase key=\"".$value."\" value=\"".$array[2][$key]."\"/>";
            }
          }

          $out .= "</trans>";
          $cache->save($out,"XMLTranslation".Mage::app()->getLocale()->getLocaleCode(),array('TRANSLATE'));
        }
      }

      $doc = new DOMDocument();
      $doc->loadXML($out);
      $node = $doc->getElementsByTagName("trans")->item(0);
      $xml_dom = new DOMDocument();
      $xml_dom->loadXML($xml->asXML());
      $node = $xml_dom->importNode($node, true);
      $xml_dom->documentElement->appendChild($node);
    } else {
      $xml_dom = new DOMDocument();
      $xml_dom->loadXML($xml->asXML());
    }

    return zetaprints_get_html_from_xml($xml_dom, $form_part, $params);
  }

  public function add_values_from_cache ($xml) {
    $session = Mage::getSingleton('customer/session');

    $text_cache = $session->getTextFieldsCache();
    $image_cache = $session->getImageFieldsCache();

    if ($text_cache && is_array($text_cache))
      foreach ($xml->Fields->Field as $field) {
        $name = (string)$field['FieldName'];

        if (!isset($text_cache[$name]))
          continue;

        $field->addAttribute('Value', $text_cache[$name]);
      }

    if ($image_cache && is_array($image_cache))
      foreach ($xml->Images->Image as $image) {
        $name = (string)$image['Name'];

        if (!isset($image_cache[$name]))
          continue;

        $image->addAttribute('Value', $image_cache[$name]);
      }
  }

  public function is_personalization_step ($context) {
    return $context->getRequest()->has('personalization') && $context->getRequest()->getParam('personalization') == '1';
  }

  public function get_next_step_url ($context) {
    if (!$this->is_personalization_step($context)) {
      //Add personalization parameter to URL
      $params = array('personalization' => '1');

      //Check if the product page was requested with reorder parameter
      //then proxy the parameter to personalization step
      if ($this->_getRequest()->has('reorder'))
        $params['reorder'] = $this->_getRequest()->getParam('reorder');

      //Check if the product page was requested with for-item parameter
      //then proxy the parameter to personalization step and ignore last
      //visited page (need it to distinguish cross-sell product and already
      //personalized product)
      if ($this->_getRequest()->has('for-item'))
        $params['for-item'] = $this->_getRequest()->getParam('for-item');
      else
        //Check that the product page was opened from cart page (need for
        //automatic first preview update for cross-sell product)
        if (strpos(Mage::getSingleton('core/session')->getData('last_url'),
              'checkout/cart') !== false)
          //Send update-first-preview query parameter to personalization step
          $params['update-first-preview'] = 1;

      //Print out url for the product
      echo $this->create_url_for_product($context->getProduct(), $params);

      return true;
    }
    else
      return false;
  }

  public function get_params_from_previous_step ($context) {
    if (!$this->is_personalization_step($context))
      return;

    foreach ($_POST as $key => $value) {
      if (is_array($value))
        foreach ($value as $option_key => $option_value)
          echo "<input type=\"hidden\" name=\"{$key}[{$option_key}]\" value=\"$option_value\" />";
      else
        echo "<input type=\"hidden\" name=\"$key\" value=\"$value\" />";
    }
  }

  public function get_product_image ($context, $product) {
    return false;
  }

  public function get_cart_image ($context) {
    $options = unserialize($context->getItem()->getOptionByCode('info_buyRequest')->getValue());

    if (!isset($options['zetaprints-previews']))
      return false;

    $images = explode(',', $options['zetaprints-previews']);

    if (count($images) == 1)
     $message = $this->__('Click to enlarge image');
    else
     $message = $this->__('Click to see more images');

    $first_image = true;

    $group = 'group-' . mt_rand();

    foreach ($images as $image) {
      $href = $this->get_preview_url($image);
      $src = $this->get_thumbnail_url($image);

      if ($first_image) {
        echo "<a class=\"in-dialog product-image\" href=\"$href\" rel=\"{$group}\" title=\"{$message}\">";
        $first_image = false;
      } else
        echo "<a class=\"in-dialog product-image\" href=\"$href\" rel=\"{$group}\" style=\"display: none\">";

      echo "<img src=\"$src\" style=\"max-width: 75px;\" />";
      echo "</a>";
    }

    //If item has low resolution link to PDF...
    if (isset($options['zetaprints-order-lowres-pdf'])) {
      $href = Mage::getStoreConfig('webtoprint/settings/url')
              . $options['zetaprints-order-lowres-pdf'];

      $title = $this->__('PDF Proof');

      //... show it
      echo "<br /><a class=\"zetaprints-lowres-pdf-link\" href=\"{$href}\">{$title}</a>";
    }
?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($) {
  $('a.in-dialog').fancybox({
    'opacity': true,
    'overlayShow': false,
    'transitionIn': 'elastic',
    'changeSpeed': 200,
    'speedIn': 500,
    'speedOut' : 500,
    'titleShow': false });
});
//]]>
</script>
<?php
    return true;
  }

  public function get_gallery_image ($context) {
    return false;
  }

  public function get_gallery_thumb ($context, $product, $_image) {
    return false;
  }

  public function get_preview_images ($context) {
    return false;
  }

  public function get_preview_image_sharing_link ($context = null) {
    $media_url = Mage::getModel('catalog/product_media_config')
                    ->getTmpMediaUrl('previews/');

    if(substr($media_url, 0, 1) == '/') {
      $scheme = $this->_getRequest()->getScheme()
              == Zend_Controller_Request_Http::SCHEME_HTTPS ? 'https' : 'http';
      $media_url = $scheme . '://' . $_SERVER['SERVER_NAME'] . $media_url;
    }
 ?>

<span class="zetaprints-share-link empty">
  <a href="javascript:void(0)"><?php echo $this->__('Share preview'); ?></a>
  <input id="zetaprints-share-link-input" type="text" value="" />
</span>

<script type="text/javascript">
//<![CDATA[
  var place_preview_image_sharing_link = true;
  var preview_image_sharing_link_template = '<?php echo $media_url; ?>';

  jQuery(document).ready(function($) {
    $('#zetaprints-share-link-input').focusout(function() {
      $(this).parent().removeClass('show');
    }).click(function () {
      $(this).select();
    }).select(function () {
      $.ajax({
        url: zp.url.preview_download,
        type: 'POST',
        dataType: 'json',
        data: 'guid=' + zp.previews[zp.current_page - 1],
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          alert(preview_sharing_link_error_text + ': ' + textStatus);
        },
        success: function (data, textStatus) {
          //Check returned status'
          if (data != 'OK')
            alert(data);
        }
      });
    }).val('');

    $('span.zetaprints-share-link a').click(function () {
      var parent = $(this).parent();

      if (!$(parent).hasClass('empty')) {
        $(parent).addClass('show');
        $('#zetaprints-share-link-input').focus();
      }
    });
  });
//]]>
</script>

<?php
  }

  public function get_preview_image ($context) {
    if (!$context->getProduct()->getSmallImage())
      return false;

    $img = '<img src="' . $context->helper('catalog/image')->init($context->getProduct(), 'small_image')->resize(265) . '" alt="'.$context->htmlEscape($context->getProduct()->getSmallImageLabel()).'" />';

    echo $context->helper('catalog/output')->productAttribute($context->getProduct(), $img, 'small_image');

    return true;
  }

  public function get_text_fields ($context) {
    $html = $this->get_form_part_html('input-fields', $context->getProduct());

    if ($html === false)
      return false;

    echo $html;
    return true;
  }

  public function get_image_fields ($context) {
    $params = array(
      'ajax-loader-image-url'
        => Mage::getDesign()->getSkinUrl('images/spinner.gif'),
      'user-image-edit-button'
        => Mage::getDesign()->getSkinUrl('images/image-edit/edit.png'),
      'photothumbnail-url-height-100-template'
        => $this->get_photo_thumbnail_url('image-guid.image-ext', 0, 100),
      'photothumbnail-url-template'
        => $this->get_photo_thumbnail_url('image-guid.image-ext')
    );

    $html = $this->get_form_part_html('stock-images', $context->getProduct(), $params);

    if ($html === false)
      return false;

    echo $html;
    return true;
  }

  private function add_user_images ($xml) {
    $url = Mage::getStoreConfig('webtoprint/settings/url');
    $key = Mage::getStoreConfig('webtoprint/settings/key');

    $user_credentials = $this->get_zetaprints_credentials();

    $data = array(
      'ID' => $user_credentials['id'],
      'Hash' => zetaprints_generate_user_password_hash($user_credentials['password']) );

    $images = zetaprints_get_user_images ($url, $key, $data);

    if ($images === null)
      return;

    foreach ($xml->Images->Image as $image_node)
      if (isset($image_node['AllowUpload']))
        foreach ($images as $image) {
          $user_image_node = $image_node->addChild('user-image');
          $user_image_node->addAttribute('guid', $image['guid']);

          if ($image['mime'] === 'image/jpeg' || $image['mime'] === 'image/jpg')
            $thumbnail_url = $this->get_photo_thumbnail_url($image['thumbnail'], 0, 100);
          else
            $thumbnail_url = $this->get_photo_thumbnail_url($image['thumbnail']);

          $user_image_node->addAttribute('thumbnail', $thumbnail_url);

          $user_image_node->addAttribute('mime', $image['mime']);
          $user_image_node->addAttribute('description', $image['description']);
          $user_image_node->addAttribute('edit-link',
            $this->_getUrl('web-to-print/image/',
              array('id' => $image['guid'], 'iframe' => 1) ));
        }
  }

  private function replace_user_input_from_order_details($template, $order_guid) {
    $url = Mage::getStoreConfig('webtoprint/settings/url');
    $key = Mage::getStoreConfig('webtoprint/settings/key');

    $order_details = zetaprints_get_order_details($url, $key, $order_guid);

    if (!$order_details)
      return;

    //Replace text field values from order details
    foreach ($template->Fields->Field as $field)
      foreach ($order_details['template-details']['pages'] as $page)
        if ($value = $page['fields'][(string) $field['FieldName']]['value']) {
          $field['Value'] = $value;
          break;
        }

    //Replace image field values from order details
    foreach ($template->Images->Image as $image)
      foreach ($order_details['template-details']['pages'] as $page)
        if ($value = $page['images'][(string) $image['Name']]['value']) {
          $image['Value'] = $value;
          break;
        }
  }

  public function get_page_tabs ($context) {
    $params = array(
      'thumbnail-url-template'
        => $this->get_thumbnail_url('image-guid.image-ext', 100, 100) );

    $html = $this->get_form_part_html('page-tabs', $context->getProduct(), $params);

    if ($html === false)
      return false;

    echo $html;
    return true;
  }

  public function get_preview_button ($context) {
    if (!$this->get_template_id($context->getProduct()))
      return false;
?>
    <div class="zetaprints-preview-button">
      <button class="update-preview button">
        <span><span><?php echo $this->__('Update preview');?></span></span>
      </button>
      <img src="<?php echo Mage::getDesign()->getSkinUrl('images/spinner.gif'); ?>" class="ajax-loader"/>
      <span class="text"><?php echo $this->__('Updating preview image');?>&hellip;</span>
    </div>
<?php
  }

  public function get_next_page_button ($context) {
    if (!$this->get_template_id($context->getProduct()))
      return false;
?>
    <div class="zetaprints-next-page-button">
      <button class="next-page button">
        <span><span><?php echo $this->__('Next page');?></span></span>
      </button>
    </div>
<?php
  }

  public function prepare_gallery_images ($context, $check_for_personalization = false) {
    if (!$this->get_template_id($context->getProduct()))
      return false;

    if ($check_for_personalization && !$this->is_personalization_step($context))
      return false;

    $images = $context->getProduct()->getMediaGalleryImages();

    foreach ($images as $image)
      if(strpos(basename($image['path']), 'zetaprints_') === 0)
        $images->removeItemByKey($image->getId());

    //$images = $context->getProduct()->getMediaGallery('images');

    //foreach ($images as &$image)
    //  if(strpos(basename($image['file']), 'zetaprints_') === 0)
    //    $image['disabled'] = 1;

    //$context->getProduct()->setMediaGallery('images', $images);
  }

  public function get_js_css_includes ($context=null) {
?>

<script type="text/javascript">
//<![CDATA[
  alert('<?php echo __FUNCTION__; ?>() function has been deprecated. See release notes in http://code.google.com/p/magento-w2p/wiki/ReleaseNotes');
//]]>
</script>

<?php
    return false;
  }

  public function get_admin_js_css_includes ($context = null) {
?>

<script type="text/javascript">
//<![CDATA[
  alert('<?php echo __FUNCTION__; ?>() function has been deprecated. See release notes in http://code.google.com/p/magento-w2p/wiki/ReleaseNotes');
//]]>
</script>

<?php
    return false;
  }

  public function get_order_webtoprint_links ($context, $item = null) {
    if ($item)
      $options = $item->getProductOptionByCode('info_buyRequest');
    else
      $options = $context->getItem()->getProductOptionByCode('info_buyRequest');

    //Check for ZetaPrints Template ID in item options
    //If it doesn't exist or product doesn't have web-to-print features then...
    if (!isset($options['zetaprints-TemplateID']))
      //... just return from the function.
      return;

    //Get value of custom option which allows users download files
    //regardless of ZP template setting
    $is_user_allowed_download = Mage::helper('webtoprint')
                              ->getCustomOptions('file-download/users@allow=1');

    //Check that downloading generated files is allowed for users
    if ($item && !$is_user_allowed_download) {
      $template = Mage::getModel('webtoprint/template')
                                      ->load($options['zetaprints-TemplateID']);

      if (!$template->getId())
        return;

      try {
        $xml = new SimpleXMLElement($template->getXml());
      } catch (Exception $e) {
        Mage::log("Exception: {$e->getMessage()}");

        return;
      }

      if (!$xml)
        return;

      $template_details = zetaprints_parse_template_details($xml);

      if (!$template_details['download'])
        return;
    }

    $webtoprint_links = "";

    $types = array('pdf', 'gif', 'png', 'jpeg');

    //If function called from admin template
    if (!$item)
      //then add CDR file type to list of available types
      array_push($types, 'cdr');

    foreach ($types as $type)
      if (isset($options['zetaprints-file-'.$type])) {
        $title = strtoupper($type);
        $webtoprint_links .= "<a class=\"zetaprints-order-file-link {$type}\" href=\"{$options['zetaprints-file-'.$type]}\" target=\"_blank\">$title</a>&nbsp;";
      }

    //Check if the item is not null (it means the function was called from admin
    //interface) and ZetaPrints Order ID option is in the item then...
    if (!$item && isset($options['zetaprints-order-id'])) {
      //... create URL to order details on web-to-print site
      $zp_order_url = Mage::getStoreConfig('webtoprint/settings/url')
                      . '?page=order-details;OrderID='
                      . $options['zetaprints-order-id'];

      //Display it on the page
      $webtoprint_links .=" <a target=\"_blank\" href=\"{$zp_order_url}\">ZP order</a>";
    }

    return $webtoprint_links;
  }

  public function get_order_preview_images ($context, $item = null) {
    if ($item)
      $options = $item->getProductOptionByCode('info_buyRequest');
    else
      $options = $context->getItem()->getProductOptionByCode('info_buyRequest');

    if (!isset($options['zetaprints-previews']))
      return;

    $previews = explode(',', $options['zetaprints-previews']);
    $group = 'group-' . mt_rand();

    $url = Mage::getStoreConfig('webtoprint/settings/url');
?>
    <tr class="border zetaprints-previews">
      <td class="last" colspan="<?php echo $item ? 5 : 10; ?>">
        <div class="zetaprints-previews-box <?php if ($item) echo 'hidden'; ?>">
          <div class="title">
            <a class="show-title">+&nbsp;<span><?php echo $this->__('Show previews');?></span></a>
            <a class="hide-title">&minus;&nbsp;<span><?php echo $this->__('Hide previews');?></span></a>
          </div>
          <div class="content">
            <ul>
            <?php foreach ($previews as $preview): ?>
              <li>
                <a class="in-dialog" href="<?php echo $this->get_preview_url($preview); ?>" target="_blank" rel="<?php echo $group; ?>">
                  <img src="<?php echo $this->get_thumbnail_url($preview); ?>" title="<?php echo $this->__('Click to enlarge image');?>"/>
                </a>
              </li>
            <?php endforeach ?>
            </ul>
          </div>
        </div>
      </td>
    </tr>
<?php
  }

  public function get_reorder_button ($context, $item) {
    $options = $item->getProductOptionByCode('info_buyRequest');

    //Check for ZetaPrints Order ID in item options
    //If it doesn't exist or product doesn't have web-to-print features then...
    if (!isset($options['zetaprints-order-id']))
      //... just return from the function.
      return;

    $product = Mage::getModel('catalog/product')->load($options['product']);

    if (!$product->getId())
      return;

    $url = $product->getUrlInStore(array('_query'
                      => array('reorder' => $options['zetaprints-order-id'])));

    echo "<a class=\"zetaprints-reorder-item-link\" href=\"{$url}\">Reorder</a>";
  }

  public function get_js_for_order_preview_images ($context) {
?>
  <script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($) {
  var $boxes = $('div.zetaprints-previews-box');

  function set_width_for_boxes () {
    var width = $('#my-orders-table, table.order-tables')
                  .find('tr.zetaprints-previews td')
                  .width();

    if (width != 0) {
      $boxes
        .find('div.content')
        .width(width - 1)
        .end()
        .removeClass('hidden');
    } else
      setTimeout(set_width_for_boxes, 1000);
  }

  function set_width_for_ul () {
    if ($('a.in-dialog img:visible').length != 0)
      $boxes.each(function () {
        var width = 0;

        $(this).find('li').each(function () {
          width += $(this).outerWidth(true);
        });

        $(this).find('ul').width(width);
      });
    else
      setTimeout(set_width_for_ul, 1000);
  }

  $(window).load(function () {
    set_width_for_boxes();
    set_width_for_ul();
  });

  $boxes.find('a.show-title').each(function () {
    $(this).click(function () {
      $(this).parents('div.zetaprints-previews-box').removeClass('hide');
    });
  });

   $boxes.find('a.hide-title').each(function () {
    $(this).click(function () {
      $(this).parents('div.zetaprints-previews-box').addClass('hide');
    });
  });

  $('a.in-dialog').fancybox({
    'opacity': true,
    'overlayShow': false,
    'transitionIn': 'elastic',
    'changeSpeed': 200,
    'speedIn': 500,
    'speedOut' : 500,
    'titleShow': false });
});
//]]>
    </script>
<?php
  }

  public function show_hide_all_order_previews ($context) {
?>
  <a href="#" class="all-order-previews">
    <span class="show-title"><?php echo $this->__('Show all order previews');?></span>
    <span class="hide-title"><?php echo $this->__('Hide all order previews');?></span>
  </a>

  <script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($) {
  $('a.all-order-previews').toggle(
    function () {
      $(this).addClass('hide-all');
      $('div.zetaprints-previews-box').addClass('hide');
    },
    function () {
      $(this).removeClass('hide-all');
      $('div.zetaprints-previews-box').removeClass('hide');
    }
  );
});
//]]>
    </script>

<?php
  }

  public function getPageSizeTable ($context, $units = 'in') {
    $params = array(
      'page-size-units' => $units,
      'page-size-icon'
                => Mage::getDesign()->getSkinUrl('images/page-size-icon.png') );

    $result = $this->get_form_part_html('page-size-table',
                                        $context->getProduct(),
                                        $params );

    echo $result ? $result : '';
  }

  public function get_js ($context) {
    if (! $template_id = $this->get_template_id($context->getProduct()))
      return false;

    $session = Mage::getSingleton('core/session');

    if (! $xml = Mage::registry('webtoprint-template-xml')) {
      $template = Mage::getModel('webtoprint/template')->loadById($template_id);

      if ($template->getId())
        try {
          $xml = new SimpleXMLElement($xml = $template->getXml());
        } catch (Exception $e) {
          Mage::log("Exception: {$e->getMessage()}");
        }
    }

    if (!$xml)
      return false;

    $template_details = zetaprints_parse_template_details($xml);

    $template_details['pages_number'] = count( $template_details['pages']);

    $product_name = $context->getProduct()->getName();

    foreach ($template_details['pages'] as $page_number => &$page_details) {
      $preview_guid = explode('preview/', $page_details['preview-image']);
      $thumb_guid = explode('thumb/', $page_details['thumb-image']);

      $preview_url = $this->get_preview_url($preview_guid[1]);

      $page_details['preview-image'] = $preview_url;
      $page_details['thumb-image']
                           = $this->get_thumbnail_url($thumb_guid[1], 100, 100);

      echo sprintf('<img src="%s" alt="Printable %s" class="zp-hidden" />',
                   $preview_url,
                   $product_name );
    }

    $previews_from_session = $session->hasData('zetaprints-previews');

    if ($previews_from_session) {
      $user_input = unserialize($session->getData('zetaprints-user-input'));

      $session->unsetData('zetaprints-previews');
    }

    //Check that the product page was opened from cart page (need for automatic
    //first preview update for cross-sell product) or was
    //requested with for-item parameter.
    $update_first_preview_on_load = $this->_getRequest()->has('for-item')
      || strpos($session->getData('last_url'), 'checkout/cart') !== false
      || (isset($_GET['update-first-preview'])
          && $_GET['update-first-preview'] == '1');

    $has_shapes = false;

    foreach ($template_details['pages'] as $page)
      if (isset($page['shapes']))
        $has_shapes = true;

    $zp_data = json_encode(array(
      'template_details' => $template_details,
      'previews_from_session' => $previews_from_session,
      'is_personalization_step' => $this->is_personalization_step($context),
      'update_first_preview_on_load' => $update_first_preview_on_load,
      'has_shapes' => $has_shapes,
      'w2p_url' => Mage::getStoreConfig('webtoprint/settings/url'),
      'options' => $this->getCustomOptions(),
      'url' => array(
        'preview' => $this->_getUrl('web-to-print/preview'),
        'preview_download' => $this->_getUrl('web-to-print/preview/download'),
        'upload' => $this->_getUrl('web-to-print/upload'),
        'image' => $this->_getUrl('web-to-print/image/update'),
        'user-image-template'
                 => $this->get_photo_thumbnail_url('image-guid.image-ext') ) ));
?>
<script type="text/javascript">
//<![CDATA[

// Global vars go here
var image_imageName = '';  //currently edited template image
var userImageThumbSelected = null;  //user selected image to edit
// Global vars end

jQuery(document).ready(function($) {
  <?php
  if (isset($user_input) && is_array($user_input))
    foreach ($user_input as $key => $value)
      echo "$('[name=$key]').val('$value');\n";
  ?>

  zp = <?php echo $zp_data ?>;

  edit_button_text = "<?php echo $this->__('Edit');?>";
  delete_button_text = "<?php echo $this->__('Delete'); ?>";
  update_preview_button_text = "<?php echo $this->__('Update preview'); ?>";
  use_image_button_text = "<?php echo $this->__('Use image'); ?>";
  selected_image_button_text = "<?php echo $this->__('Selected image'); ?>";

  preview_generation_response_error_text = "<?php echo $this->__('Can\'t get preview image:'); ?>";
  preview_generation_error_text = "<?php echo $this->__('There was an error in generating or receiving preview image.\nPlease try again.'); ?>";
  preview_sharing_link_error_text = "<?php echo $this->__('Error was occurred while preparing preview image'); ?>";
  uploading_image_error_text = "<?php echo $this->__('Error was occurred while uploading image'); ?>";
  notice_to_update_preview_text = "<?php echo $this->__('Update preview first!'); ?>";
  notice_to_update_preview_text_for_multipage_template = "<?php echo $this->__('Update all previews first!'); ?>";

  click_to_close_text = "<?php echo $this->__('Click to close'); ?>";
  click_to_view_in_large_size = "<?php echo $this->__('Click to view in large size');?>";
  click_to_delete_text = "<?php echo $this->__('Click to delete'); ?>";
  click_to_edit_text = "<?php echo $this->__('Click to edit'); ?>";

  cant_delete_text = "<?php echo $this->__('Can\'t delete image'); ?>";
  delete_this_image_text = "<?php echo $this->__('Delete this image?'); ?>";

  personalization_form.apply(zp, [$]);
});
//]]>
</script>
<?php
  }
}
?>
