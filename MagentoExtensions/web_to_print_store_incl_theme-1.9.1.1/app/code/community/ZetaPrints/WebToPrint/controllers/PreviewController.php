<?php

class ZetaPrints_WebToPrint_PreviewController
  extends Mage_Core_Controller_Front_Action
  implements ZetaPrints_Api {

  public function indexAction () {
    $params = array();

     //Preparing params for image generating request to zetaprints
    foreach ($this->getRequest()->getParams() as $key => $value) {
      //Ignore key if it doesn't start with 'zetaprints-' prefix
      if (strpos($key, 'zetaprints-') !== 0)
        continue;

      //Remove prefix from the key
      $_key = substr($key, 11);

      //Text and image template fields distinguish by prefix in its name
      //Prefix for text fields is '_' sign, for image fields is '#' sign.
      //Metadata fields for text and image fields prepends prefix
      //with '*' sign, i.e. '*_' for text fields and '*#' for image fields.
      //So POST fields have 1- or 2-letter prefixes.

      //Determine length of field prefix
      $prefix_length = 1;
      if (strpos($_key, '*') === 0)
        $prefix_length = 2;

      //Process field name (key), restore original symbols
      $_key = substr($_key, 0, $prefix_length)
              . str_replace( array('_', "\x0A"),
                             array(' ', '.'),
                             substr($_key, $prefix_length) );

      //Add token to the array
      $params[$_key] = $value;
    }

    if(count($params) == 0)
      return;

    //$session = Mage::getSingleton('customer/session');

    //$text_cache = $session->getTextFieldsCache();
    //if (!$text_cache)
    //  $text_cache = array();

    //$image_cache = $session->getImageFieldsCache();
    //if (!$image_cache)
    //  $image_cache = array();

    //foreach ($params as $key => $value)
    //  if (strpos($key, '_') !== false) {
    //    $_key = substr($key, 1);

    //    if (array_key_exists($_key, $text_cache))
    //      unset($text_cache[$_key]);

    //    if ($value)
    //      $text_cache[$_key] = $value;

    //    if ($length = count($text_cache) > 150)
    //      $text_cache = array_slice($text_cache, $length - 150);
    //  } elseif (strpos($key, '#') !== false) {
    //    $_key = substr($key, 1);

    //    if (array_key_exists($_key, $image_cache))
    //      unset($image_cache[$_key]);

    //    if ($value)
    //      $image_cache[$_key] = $value;

    //    if ($length = count($image_cache) > 50)
    //      $image_cache = array_slice($image_cache, $length - 50);
    //  }

    //$session->setTextFieldsCache($text_cache);
    //$session->setImageFieldsCache($image_cache);

    //reset($params);

    $user_credentials = Mage::helper('webtoprint')
                          ->get_zetaprints_credentials();
    $params['ID'] = $user_credentials['id'];
    $params['Hash'] = zetaprints_generate_user_password_hash($user_credentials['password']);

    $url = Mage::getStoreConfig('webtoprint/settings/url');
    $key = Mage::getStoreConfig('webtoprint/settings/key');

    $templates_details = zetaprints_update_preview($url, $key, $params);

    if (!$templates_details)
      return;

    $helper = Mage::helper('webtoprint');

    //Generate URLs for preview and thumbnail images
    foreach ($templates_details['pages'] as &$page)
      if (isset($page['updated-preview-image'])) {
        $page['updated-preview-url'] = $helper
                  ->get_preview_url(substr($page['updated-preview-image'], 8));
        $page['updated-thumb-url'] = $helper
                  ->get_thumbnail_url(substr($page['updated-preview-image'], 8),
                                      100, 100);
      }

    echo json_encode($templates_details);
  }

  public function getAction () {
    if (!$this->getRequest()->has('guid'))
        return;

    $guid = $this->getRequest()->get('guid');

    $url = Mage::getStoreConfig('webtoprint/settings/url') . '/preview/'
           . $guid;

    $response = zetaprints_get_content_from_url($url);

    if (!zetaprints_has_error($response)) {
      $headers = $response['content']['header'];

      if (is_array($headers))
        $this->getResponse()
          ->setHeader('Last-Modified', $headers['Last-Modified'], true)
          ->setHeader('ETag', $headers['ETag'], true)
          ->setHeader('Pragma', '', true)
          ->setHeader('Cache-Control', 'public', true)
          ->setHeader('Cache-Control', $headers['Cache-Control'])
          ->setHeader('Expires', '', true)
          ->setHeader('Content-Type', $headers['Content-Type'] , true)
          ->setHeader('Content-Length', $headers['Content-Length'], true);
      else {
        $type = explode('.', $guid);

        if (count($type) == 2)
          $type = $type[1];

        if ($type == 'jpg')
          $type = 'jpeg';

        $this->getResponse()
          ->setHeader('Content-Type', 'image/' . $type);
        }

      $this->getResponse()->setBody($response['content']['body']);
    }
  }

  public function downloadAction () {
    if (!$this->getRequest()->has('guid'))
        return;

    $guid = $this->getRequest()->get('guid');

    $media_config = Mage::getModel('catalog/product_media_config');

    $file_path = $media_config->getTmpMediaPath("previews/{$guid}");

    //Check that preview was already downloaded
    //to prevent subsequent downloads
    if (file_exists($file_path)) {
      echo json_encode('OK');
      return;
    }

    $url = Mage::getStoreConfig('webtoprint/settings/url') . '/preview/'
           . $guid;

    //Download preview image from ZetaPrinrs
    $response = zetaprints_get_content_from_url($url);

    if (zetaprints_has_error($response)) {
      echo json_encode($this->__('Error was occurred while preparing preview image'));
      return;
    }

    //Save preview image on M. server
    if (file_put_contents($file_path, $response['content']['body']) === false) {
      echo json_encode($this->__('Error was occurred while preparing preview image'));
      return;
    }

    echo json_encode('OK');
  }
}
?>
