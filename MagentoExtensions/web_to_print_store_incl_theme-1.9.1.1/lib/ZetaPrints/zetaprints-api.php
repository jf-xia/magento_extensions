<?php

define("ZP_API_VER", '2.0.0');

require_once 'mage-logging.php';

function zetaprints_generate_guid () {
  return strtoupper(sprintf('%04x%04x-%04x-%03x4-%04x-%04x%04x%04x',
    mt_rand(0, 65535), mt_rand(0, 65535),
    mt_rand(0, 65535),
    mt_rand(0, 4095),
    bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
    mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) ));
}

function zetaprints_generate_password () {
  return substr(md5(time()), 0, 6);
}

/**
 * Generate md5 hash from user's password and server ip address.
 *
 * Param password - user's password
 * Returns string contains hash
 */
function zetaprints_generate_user_password_hash ($password) {
  _zetaprints_debug();
  $ip = $_SERVER["SERVER_ADDR"];

  //Enter the external ip address here
  //if it doesn't match the server's address (e.g. translated by a router)
  //$ip = 'a.b.c.d';

  _zetaprints_debug("Server IP: {$ip}");

  return md5($password.$ip);
}

function _zetaprints_string_to_date ($value) {
  if (!$value)
    return date('Y-m-d h:i:s');

  return date('Y-m-d h:i:s', strtotime($value));
}

/**
 * Transform template details xml to html form.
 *
 * Param template_xml - string contains template details xml
 * Returns string contains html form
 */
function zetaprints_get_html_from_xml ($xml, $xslt, $params) {
  if (is_string($xml)) {
    $xml_dom = new DOMDocument();
    $xml_dom->loadXML($xml);
  } else
    $xml_dom = $xml;

  $xslt_dom = new DOMDocument();
  $xslt_dom->load(dirname(__FILE__).'/xslt/' . $xslt . '-html.xslt');

  $proc = new XSLTProcessor();
  $proc->importStylesheet($xslt_dom);

  $proc->setParameter('', $params);
  return $proc->transformToXML($xml_dom);
}

function zetaprints_get_list_of_catalogs ($url, $key) {
  _zetaprints_debug();
  $response = zetaprints_get_content_from_url("$url/API.aspx?page=api-catalogs;ApiKey=$key");

  if (zetaprints_has_error($response))
    return $response['content'];

  try {
    $xml = new SimpleXMLElement($response['content']['body']);
  } catch (Exception $e) {
    _zetaprints_debug("Exception: {$e->getMessage()}");
    return null;
  }


  $catalogs = array();

  foreach ($xml->channel[0]->item as $item)
    $catalogs[] = array('title' => (string)$item->title,
                        'link' => (string)$item->link,
                        'guid' => (string)$item->id,
                        'domain' => (string)$item->domain,
                        'templates' => (int)$item->templates,
                        'users' => (int)$item->users,
                        'orders' => (int)$item->orders,
                        'created' => strtotime($item->created),
                        'public' => (string)$item->access == 'public' ? true : false,
                        'keywords' => (string)$item->keywords);

  _zetaprints_debug(array('catalogs' => $catalogs));

  return $catalogs;
}

function zetaprints_get_templates_from_catalog ($url, $key, $catalog_guid) {
  _zetaprints_debug();

  $response = zetaprints_get_content_from_url("$url/API.aspx?page=api-templates;CorporateID=$catalog_guid;ApiKey=$key");

  if (zetaprints_has_error($response))
    return $response['content'];

  try {
    $xml = new SimpleXMLElement($response['content']['body']);
  } catch (Exception $e) {
    _zetaprints_debug("Exception: {$e->getMessage()}");
    return null;
  }

  $templates = array();

  foreach ($xml->channel[0]->item as $item)
    $templates[] = array('title' => (string)$item->title,
                         'link' => (string)$item->link,
                         'guid' => (string)$item->id,
                         'catalog_guid' => (string)$item->cid,
                         'description' => (string)$item->description,
                         'date'
                             => _zetaprints_string_to_date($item->lastModified),
                         'thumbnail' => (string)$item->thumbnail,
                         'image' => (string)$item->image);

  _zetaprints_debug(array('templates' => $templates));

  return $templates;
}

function zetaprints_parse_template_details ($xml) {
  $download = false;

  if (isset($xml['Download']) && ((string)$xml['Download'] == 'allow'
      || (string)$xml['Download'] == 'only'))
    $download = true;

  $template = array('guid' => (string) $xml['TemplateID'],
                     'corporate-guid' => (string) $xml['CorporateID'],
                     'created' => _zetaprints_string_to_date($xml['Created']),
                     'comments' => (string) $xml['Comments'],
                     'url' => (string) $xml['AccessURL'],
                     'product-reference' => (string) $xml['ProductReference'],
                     'download' => $download,
                     'pdf' => isset($xml['GeneratePdf'])
                                  ? (bool) $xml['GeneratePdf'] : false,
                     'jpeg' => isset($xml['GenerateJpg'])
                                  ? (bool) $xml['GenerateJpg'] : false,
                     'png' => isset($xml['GenerateGifPng'])
                                  ? (bool) $xml['GenerateGifPng'] : false );

  if (!$xml->Pages->Page) {
    _zetaprints_debug("No pages in tempalate [{$template['guid']}]");

    return $template;
  }

  $template['pages'] = array();

  $page_number = 1;

  $field_to_shape_mapping = array();

  foreach ($xml->Pages->Page as $page) {
    $template['pages'][$page_number] = array(
      'name' => (string) $page['Name'],
      'preview-image' => (string) $page['PreviewImage'],
      'thumb-image' => (string) $page['ThumbImage'],
      'static' => isset($page['Static']) ? (bool) $page['Static'] : false,
      'width-in' => (string) $page['WidthIn'],
      'height-in' => (string) $page['HeightIn'],
      'width-cm' =>  (float) $page['WidthCm'],
      'height-cm' =>  (float) $page['HeightCm'] );

    if ((string) $page['PreviewImageUpdated'])
      $template['pages'][$page_number]['updated-preview-image']
                                        = (string) $page['PreviewImageUpdated'];

    //Check for templates with old shape coordinates system
    $is_page_2_box_empty = (string) $page['Page2BoxX'] == ''
                            && (string)$page['Page2BoxY'] == ''
                            && (string)$page['Page2BoxW'] == ''
                            && (string)$page['Page2BoxH'] == '';

    //Ignore shapes with old coordinates system
    if (!$is_page_2_box_empty && $page->Shapes) {
      $template['pages'][$page_number]['shapes'] = array();

      $field_to_shape_mapping[$page_number] = array();

      foreach ($page->Shapes->Shape as $shape) {
        $name = (string) $shape['Name'];
        $template['pages'][$page_number]['shapes'][$name] = array(
          'name' => $name,
          'x1' => (float) $shape['X1'],
          'y1' => (float) $shape['Y1'],
          'x2' => (float) $shape['X2'],
          'y2' => (float) $shape['Y2'],
          'anchor-x' => (float) $shape['AnchorX'],
          'anchor-y' => (float) $shape['AnchorY'],
          'hidden' => $page_number > 1,
          'has-value' => false );

        foreach (explode('; ', $name) as $_name)
          $field_to_shape_mapping[$page_number][$_name] = $name;
      }

      $template['pages'][$page_number]['shapes'] =
                      array_reverse($template['pages'][$page_number]['shapes']);
    }

    $page_number++;
  }

  foreach ($xml->Images->Image as $image) {
    $image_array = array(
      'name' => (string) $image['Name'],
      'width' => (string) $image['Width'],
      'height' => (string) $image['Height'],
      'color-picker' => isset($image['ColourPicker'])
                            ? (string) $image['ColourPicker'] : null,
      'allow-upload' => isset($image['AllowUpload'])
                            ? (bool) $image['AllowUpload'] : false,
      'allow-url' => isset($image['AllowUrl'])
                            ? (bool) $image['AllowUrl'] : false,
      'clipped' => isset($image['Clipped'])
                            ? (bool) $image['Clipped'] : false,
      //We get lowercase GUID in value for user images.
      //Convert to uppercase while the issue will be fixed in ZP side
      'value' => isset($image['Value'])
                   ? strtoupper((string) $image['Value']) : null );

    if ($image->StockImage) {
      $image_array['stock-images'] = array();

      foreach ($image->StockImage as $stock_image)
        $image_array['stock-images'][] = array(
          'guid' => (string) $stock_image['FileID'],
          'mime' => (string) $stock_image['MIME'],
          'thumb' => (string) $stock_image['Thumb']
        );
    }

    $page_number = (int) $image['Page'];

    if (!isset($template['pages'][$page_number]['images']))
      $template['pages'][$page_number]['images'] = array();

    $template['pages'][$page_number]['images'][(string) $image['Name']]
                                                                = $image_array;

    if (isset($field_to_shape_mapping[$page_number][$image_array['name']])) {
      $shape_name = $field_to_shape_mapping[$page_number][$image_array['name']];

      $shape = & $template['pages']
                          [$page_number]
                          ['shapes']
                          [$shape_name];

      if ($image_array['value'])
        $shape['has-value'] = true;

      if ($page_number > 1)
        $shape['hidden'] = false;
    }
  }

  foreach ($xml->Fields->Field as $field) {
    $field_array = array(
      'name' => (string) $field['FieldName'],
      'hint' => (string) $field['Hint'],
      'min-length' => isset($field['MinLen']) ? (int) $field['MinLen'] : null,
      'max-length' => isset($field['MaxLen']) ? (int) $field['MaxLen'] : null,
      'multiline' => isset($field['Multiline'])
                        ? (bool) $field['Multiline'] : false,
      'colour-picker' => isset($field['ColourPickerFill'])
                           ? (string) $field['ColourPickerFill'] : null,
      'story' => isset($field['Story'])
                           ? (string) $field['Story'] : null,
      'story-as-default' => isset($field['StoryAsDefault'])
                           ? (int) $field['StoryAsDefault'] : null,
      'combobox' => isset($field['Combobox'])
                           ? (bool) $field['Combobox'] : false,
      'value' => isset($field['Value'])
                   ? (string) $field['Value'] : null );

    if ($field->Value) {
      $field_array['values'] = array();

      foreach ($field->Value as $value)
        $field_array['values'][] = (string) $value;
    }

    if (isset($field['Meta'])) {
      $field_array['metadata'] = array();

      foreach (explode(';', (string) $field['Meta']) as $token)
        if ($token) {
          list($key, $value) = explode('=', $token);
          $field_array['metadata'][$key] = $value;
        }
    }

    $page_number = (int) $field['Page'];

    if (!isset($template['pages'][$page_number]['fields']))
      $template['pages'][$page_number]['fields'] = array();

    $template['pages'][$page_number]['fields'][(string) $field['FieldName']]
                                                                = $field_array;

    if (isset($field_to_shape_mapping[$page_number][$field_array['name']])) {
      $shape_name = $field_to_shape_mapping[$page_number][$field_array['name']];

      $shape = & $template['pages']
                          [$page_number]
                          ['shapes']
                          [$shape_name];

      if ($field_array['value'])
        $shape['has-value'] = true;

      if ($page_number > 1)
        $shape['hidden'] = false;
    }
  }

  if ($xml->Tags) {
    $tags = array();

    foreach ($xml->Tags->Tag as $tag) {
      $tags[] = (string) $tag;
    }

    if (count($tags))
      $template['tags'] = $tags;
  }

  _zetaprints_debug(array('template' => $template));

  return $template;
}

function zetaprints_get_template_detailes ($url, $key, $template_guid) {
  _zetaprints_debug();

  $response = zetaprints_get_content_from_url("$url/API.aspx?page=api-template;TemplateID=$template_guid;ApiKey=$key");

  if (zetaprints_has_error($response))
    return null;

  try {
    $xml = new SimpleXMLElement($response['content']['body']);
  } catch (Exception $e) {
    _zetaprints_debug("Exception: {$e->getMessage()}");
    return null;
  }

  return zetaprints_parse_template_details($xml);
}

function zetaprints_get_template_details_as_xml ($url, $key, $template_guid,
                                                 $data = null) {
  _zetaprints_debug();

  $response = zetaprints_get_content_from_url("$url/API.aspx?page=api-template;TemplateID=$template_guid;ApiKey=$key", $data);

  if (zetaprints_has_error($response))
    return null;

  return $response['content']['body'];
}

function zetaprints_parse_order_details ($xml) {
  $order = array(
    'guid' => (string) $xml['OrderID'],
    'created-by' => (string) $xml['CreatedBy'],
    'created' => _zetaprints_string_to_date($xml['Created']),
    'status' => (string) $xml['Status'],
    'billed-by-zp' => _zetaprints_string_to_date($xml['BilledByZP']),
    'status-history' => (string) $xml['StatusHistory'],
    'product-price' => (float) $xml['ProductPrice'],
    'product-name' => (string) $xml['ProductName'],
    'pdf' => (string) $xml['PDF'],
    'cdr' => (string) $xml['CDR'],
    'gif' => (string) $xml['GIF'],
    'png' => (string) $xml['PNG'],
    'jpeg' => (string) $xml['JPEG'],
    'approval-email' => (string) $xml['ApprovalEmail'],
    'note' => (string) $xml['Note'],
    'cost-centre' => (string) $xml['CostCentre'],
    'delivery-address' => (string) $xml['DeliveryAddress'],
    'quantity-price-choice' => (string) $xml['QuantityPriceChoice'],
    'optional-choice' => (string) $xml['OptionalChoice'],
    'user-reference' => (string) $xml['UserReference'],
    'paid-date-time' => (string) $xml['PaidDateTime'],
    'currency' => (string) $xml['Currency'],
    'delivery-street-1' => (string) $xml['DeliveryStreet1'],
    'delivery-street-2' => (string) $xml['DeliveryStreet2'],
    'delivery-town' => (string) $xml['DeliveryTown'],
    'delivery-state' => (string) $xml['DeliveryState'],
    'delivery-zip' => (string) $xml['DeliveryZip'],
    'delivery-country' => (string) $xml['DeliveryCountry'] );

  $order['template-details'] =
                      zetaprints_parse_template_details($xml->TemplateDetails);

  _zetaprints_debug(array('order' => $order));

  return $order;
}

function zetaprints_get_order_details ($url, $key, $order_id) {
  _zetaprints_debug();

  $response = zetaprints_get_content_from_url("$url/api.aspx?page=api-order;ApiKey=$key;OrderID=$order_id");

  if (zetaprints_has_error($response))
    return null;

  try {
    $xml = new SimpleXMLElement($response['content']['body']);
  } catch (Exception $e) {
    _zetaprints_debug("Exception: {$e->getMessage()}");
    return null;
  }

  return zetaprints_parse_order_details($xml);
}

function zetaprints_change_order_status ($url, $key, $order_id, $old_status, $new_status) {
  _zetaprints_debug();

  $response = zetaprints_get_content_from_url("$url/API.aspx?page=api-order-status;ApiKey=$key;OrderID=$order_id",
                                              array('Status' => $new_status,
                                                    'StatusOld' => $old_status) );

  if (zetaprints_has_error($response))
    return null;

  try {
    $xml = new SimpleXMLElement($response['content']['body']);
  } catch (Exception $e) {
    _zetaprints_debug("Exception: {$e->getMessage()}");
    return null;
  }

  return zetaprints_parse_order_details($xml);
}

function zetaprints_update_preview ($url, $key, $data) {
  _zetaprints_debug();

  $data['Xml'] = 1;

  $response = zetaprints_get_content_from_url("$url/API.aspx?page=api-preview;ApiKey=$key", $data);

  if (zetaprints_has_error($response))
    return null;

  try {
    $xml = new SimpleXMLElement($response['content']['body']);
  } catch (Exception $e) {
    _zetaprints_debug("Exception: {$e->getMessage()}");
    return null;
  }

  return zetaprints_parse_template_details($xml);
}

function zetaprints_get_preview_image_url ($url, $key, $data) {
  _zetaprints_debug();

  $response = zetaprints_get_content_from_url("$url/API.aspx?page=api-preview;ApiKey=$key", $data);

  if (zetaprints_has_error($response))
    return null;

  return $response['content']['body'];
}

function zetaprints_get_user_images ($url, $key, $data) {
  _zetaprints_debug();

  $response = zetaprints_get_content_from_url("$url/API.aspx?page=api-imgs;ApiKey=$key", $data);

  if (zetaprints_has_error($response))
    return null;

  try {
    $xml = new SimpleXMLElement($response['content']['body']);
  } catch (Exception $e) {
    _zetaprints_debug("Exception: {$e->getMessage()}");
    return null;
  }

  $images = array();

  foreach ($xml->Image as $image)
    $images[] = array('folder' => (string)$image['Folder'],
                      'guid' => (string)$image['ImageID'],
                      'created'
                               => _zetaprints_string_to_date($image['Created']),
                      'used' => _zetaprints_string_to_date($image['Used']),
                      'updated' =>
                                  _zetaprints_string_to_date($image['Updated']),
                      'file_guid' => (string)$image['FileID'],
                      'mime' => (string)$image['MIME'],
                      'thumbnail' => (string)$image['Thumb'],
                      'thumbnail_width' => (int)$image['ThumbWidth'],
                      'thumbnail_height' => (int)$image['ThumbHeight'],
                      'width' => (int)$image['ImageWidth'],
                      'height' => (int)$image['ImageHeight'],
                      'description' => (string)$image['Description'],
                      'length' => (int)$image['Length'] );

  _zetaprints_debug(array('images' => $images));

  return $images;
}

function zetaprints_download_customer_image ($url, $key, $data) {
  _zetaprints_debug();

  $response = zetaprints_get_content_from_url("$url/API.aspx?page=api-img-new;ApiKey=$key", $data);

  if (zetaprints_has_error($response))
    return null;

  try {
    $xml = new SimpleXMLElement($response['content']['body']);
  } catch (Exception $e) {
    _zetaprints_debug("Exception: {$e->getMessage()}");
    return null;
  }

  if (count($xml->Image) != 1) {
    _zetaprints_debug('Number of uploaded customer images is ' . count($xml->Image));
    return null;
  }

  $images = array();

  foreach ($xml->Image as $image)
    $images[] = array('folder' => (string)$image['Folder'],
                      'guid' => (string)$image['ImageID'],
                      'created' =>
                                  _zetaprints_string_to_date($image['Created']),
                      'used' => _zetaprints_string_to_date($image['Used']),
                      'updated' =>
                                  _zetaprints_string_to_date($image['Updated']),
                      'file_guid' => (string)$image['FileID'],
                      'mime' => (string)$image['MIME'],
                      'thumbnail' => (string)$image['Thumb'],
                      'thumbnail_width' => (int)$image['ThumbWidth'],
                      'thumbnail_height' => (int)$image['ThumbHeight'],
                      'width' => (int)$image['ImageWidth'],
                      'height' => (int)$image['ImageHeight'],
                      'description' => (string)$image['Description'],
                      'length' => (int)$image['Length'] );

  _zetaprints_debug(array('images' => $images));

  return $images;
}

function zetaprints_get_edited_image_url ($url, $key, $data) {
  _zetaprints_debug();

  if (!isset($data['action']) || strlen($data['action']) == 0) {
    _zetaprints_debug('No picture edit action specified');

    return null;
  }

  $action = $data['action'];
  unset($data['action']);

  $response = zetaprints_get_content_from_url("{$url}/API.aspx?page=api-{$action};ApiKey={$key}", $data);

  if (zetaprints_has_error($response))
    return null;

  return $response['content']['body'];
}

function zetaprints_create_order ($url, $key, $data) {
  _zetaprints_debug();

  $data['Xml'] = 1;

  $response = zetaprints_get_content_from_url("$url/api.aspx?page=api-order-save;ApiKey=$key", $data);

  if (zetaprints_has_error($response))
    return null;

  try {
    $xml = new SimpleXMLElement($response['content']['body']);
  } catch (Exception $e) {
    _zetaprints_debug("Exception: {$e->getMessage()}");
    return null;
  }

  return zetaprints_parse_order_details($xml);
}

function zetaprints_get_order_id ($url, $key, $data) {
  _zetaprints_debug();

  $response = zetaprints_get_content_from_url("$url/api.aspx?page=api-order-save;ApiKey=$key", $data);

  if (zetaprints_has_error($response))
    return null;

  return $response['content']['body'];
}

function zetaprints_complete_order ($url, $key, $order_guid, $new_guid = null) {
  _zetaprints_debug();

  if ($new_guid)
    $new_guid_parameter = ";IDs={$new_guid}";
  else
    $new_guid_parameter = '';

  $response = zetaprints_get_content_from_url("$url/api.aspx?page=api-order-complete;ApiKey=$key;OrderID=$order_guid{$new_guid_parameter}");

  if (zetaprints_has_error($response))
    return null;

  try {
    $xml = new SimpleXMLElement($response['content']['body']);
  } catch (Exception $e) {
    _zetaprints_debug("Exception: {$e->getMessage()}");
    return null;
  }

  return zetaprints_parse_order_details($xml);
}

function zetaprints_register_user ($url, $key, $user_id, $password, $corporate_id = null) {
  _zetaprints_debug();

  $request_url = "$url/api.aspx?page=api-user-new;ApiKey=$key;UserID=$user_id;Password=$password";

  if ($corporate_id && is_string($corporate_id) && count($corporate_id))
    $request_url .= ";CorporateID=$corporate_id";

  $response = zetaprints_get_content_from_url($request_url);

  if (zetaprints_has_error($response))
    return null;

  return strpos($response['content']['body'], '<ok />') !== false ? true : false;
}

function _zetaprints_parse_http_headers ($headers_string) {
  $lines = explode("\r\n", $headers_string);

  $headers = array();

  foreach ($lines as $line) {
    $key_value = explode(': ', $line);

    if (count($key_value) == 2)
      $headers[$key_value[0]] = $key_value[1];
    else
      $headers[] = $key_value[0];
  }

  return $headers;
}

function _zetaprints_return ($content, $error = false) {
  return array('error' => $error, 'content' => $content);
}

function _zetaprints_ok ($content) {
  return _zetaprints_return($content);
}

function _zetaprints_error ($message) {
  return _zetaprints_return($message, true);
}

function zetaprints_has_error ($response) {
  return !is_array($response) || !isset($response['error']) || !isset($response['content']) || $response['error'];
}

function zetaprints_get_content_from_url ($url, $data = null) {
  _zetaprints_debug();

  $options = array(CURLOPT_URL => $url,
                   CURLOPT_HEADER => true,
                   CURLOPT_CRLF => true,
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_HTTPHEADER => array('Expect:') );

  if ($data) {
    $data_encoded = array();

    while (list($key, $value) = each($data))
      $data_encoded[] = urlencode($key).'='.urlencode($value);

    $options[CURLOPT_POSTFIELDS] = implode('&', $data_encoded);
  }

  _zetaprints_debug(array('curl options' => $options));

  $curl = curl_init();

  if (!curl_setopt_array($curl, $options)) {
    _zetaprints_debug("Can't set options for curl");
    return _zetaprints_error("Can't set options for curl");
  }

  $output = curl_exec($curl);
  $info = curl_getinfo($curl);

  if ($output === false || $info['http_code'] != 200) {
    $zetaprins_message = '';

    if ($output !== false) {
      $output = explode("\r\n\r\n", $output);

      if (function_exists('http_parse_headers'))
        $headers = http_parse_headers($output[0]);
      else
        $headers = _zetaprints_parse_http_headers($output[0]);

      $zetaprins_message = (is_array($headers) && isset($headers['X-ZP-API-Error-Msg'])) ? $headers['X-ZP-API-Error-Msg'] : '';
    }

    $curl_error_message = curl_error($curl);
    curl_close($curl);

    _zetaprints_debug(array('Error' => $curl_error_message, 'Curl info' => $info, 'Data' => $output));
    return _zetaprints_error('Zetaprints error: ' . $zetaprins_message . '; Curl error: ' . $curl_error_message);
  }

  curl_close($curl);

  list($headers, $content) = explode("\r\n\r\n", $output, 2);

  if (function_exists('http_parse_headers'))
    $headers = http_parse_headers($headers);
  else
    $headers = _zetaprints_parse_http_headers($headers);

  if (isset($info['content_type'])) {
    $type = explode('/', $info['content_type']);

    if ($type[0] == 'image')
      _zetaprints_debug(array('header' => $headers, 'body' => 'Image'));
    else
      _zetaprints_debug(array('header' => $headers, 'body' => $content));
  } else
    _zetaprints_debug(array('header' => $headers, 'body' => $content));

  return _zetaprints_ok(array('header' => $headers, 'body' => $content));
}

?>
