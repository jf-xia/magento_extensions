<?php
/**
 * OpenERP data helper
 */
class ZetaPrints_WebToPrint_Helper_Data extends Mage_Core_Helper_Abstract {

  //ZetaPrints cookie life time in seconds (180 days)
  const COOKIE_LIFETIME = 15552000;

  public function _getUrl($route, $params = array()) {
    if ($this->_getRequest()->getScheme() == Zend_Controller_Request_Http::SCHEME_HTTPS) {
      $params['_secure'] = true;
      return parent::_getUrl($route, $params);
    }

    return parent::_getUrl($route, $params);
  }

  public function get_preview_url ($guid) {
    if ($this->_getRequest()->getScheme() == Zend_Controller_Request_Http::SCHEME_HTTPS)
      return parent::_getUrl('web-to-print/preview/get',
                              array('guid' => $guid, '_secure' => true) );

    return Mage::getStoreConfig('webtoprint/settings/url') . '/preview/'
           . $guid;
  }

  public function get_thumbnail_url ($guid, $width = 0, $height = 0) {
    if ($this->_getRequest()->getScheme() == Zend_Controller_Request_Http::SCHEME_HTTPS)
      return parent::_getUrl('web-to-print/thumbnail/get',
                              array('guid' => $guid, 'width' => $width,
                              'height' => $height, '_secure' => true) );

    //Check if width or height is setted
    if (($width + $height) != 0)
      $guid = str_replace('.', "_{$width}x{$height}.", $guid);

    return Mage::getStoreConfig('webtoprint/settings/url') . '/thumb/' . $guid;
  }

  public function get_photo_thumbnail_url ($guid, $width = 0, $height = 0) {
    if ($this->_getRequest()->getScheme() == Zend_Controller_Request_Http::SCHEME_HTTPS)
      return parent::_getUrl('web-to-print/photothumbnail/get',
                              array('guid' => $guid, 'width' => $width,
                              'height' => $height, '_secure' => true) );

    //Check if width or height is setted
    if (($width + $height) != 0)
      $guid = str_replace('.', "_{$width}x{$height}.", $guid);

    return Mage::getStoreConfig('webtoprint/settings/url') . '/photothumbs/'
           . $guid;
  }

  public function get_image_editor_url ($guid) {
    if ($this->_getRequest()->getScheme() == Zend_Controller_Request_Http::SCHEME_HTTPS)
      return parent::_getUrl('web-to-print/image/',
                              array('id' => $guid, 'iframe' => 1,
                                    '_secure' => true) );

    return parent::_getUrl('web-to-print/image/',
                            array('id' => $guid, 'iframe' => 1) );
  }

  public function create_url_for_product ($product, $query_params) {
    //Get model for URL
    $url_model = $product->getUrlModel();

    $params = array();

    //Set parameter for Session ID in URL
    if (!Mage::app()->getUseSessionInUrl())
      $params['_nosid'] = true;

    //Add query parameters to URL
    $params['_query'] = $query_params;

    return $url_model->getUrl($product, $params);
  }

  protected function replace_template_values_from_cart_item ($template, $item_id) {
    $item = Mage::getSingleton('checkout/session')
              ->getQuote()
              ->getItemById($item_id);

    if (!($item && $item->getId()))
      return;

    $option_model = $item->getOptionByCode('info_buyRequest');
    $options = unserialize($option_model->getValue());

    //Item previews stored as comma-separated string in a quote.
    //Convert it to array.
    //$previews = explode(',', $options['zetaprints-previews']);

    //Replace previews in XML
    //foreach ($previews as $index => $preview) {
    //  $template->Pages->Page[$index]['PreviewImage'] = "preview/{$preview}";
    //  $template->Pages->Page[$index]['ThumbImage'] = "thumb/{$preview}";
    //}

    $fields = array();

    //Prepare fields' values
    foreach ($options as $key => $value)
      if (strpos($key, 'zetaprints-') !== false) {
        $key = substr($key, 11);

        if (strpos($key, '#') === 0 || strpos($key, '_') === 0) {
          $key = str_replace(array('_', "\x0A"), array(' ', '.'), substr($key, 1));

          $fields[$key] = $value;
        }
      }

    //Replace text field values in XML
    foreach ($template->Fields->Field as $field) {
      $name = (string) $field['FieldName'];

      if (isset($fields[$name]))
        $field['Value'] = $fields[$name];
    }

    //Replace image field values in XML
    foreach ($template->Images->Image as $image) {
      $name = (string) $image['Name'];

      if (isset($fields[$name]))
        $image['Value'] = $fields[$name];
    }
  }

  public function replace_preview_images ($template, $previews) {
    $page_number = 0;

    foreach ($template->Pages->Page as $page) {
      $guid = $previews[$page_number++];

      $page['PreviewImageUpdated'] = $this->get_preview_url($guid);
      $page['ThumbImageUpdated'] = $this->get_thumbnail_url($guid, 100, 100);
    }
  }

  public function update_preview_images_urls ($template) {
    foreach ($template->Pages->Page as $page) {
      $preview_guid = explode('preview/', (string) $page['PreviewImage']);
      $thumb_guid = explode('thumb/', (string) $page['ThumbImage']);

      $page['PreviewImage'] = $this->get_preview_url($preview_guid[1]);
      $page['ThumbImage'] = $this->get_thumbnail_url($thumb_guid[1], 100, 100);
    }
  }

  function get_zetaprints_credentials () {
    $session = Mage::getSingleton('customer/session');

    if ($has_customer = $session->isLoggedIn()) {
      $customer = $session->getCustomer();

      if ($id = $customer->getZetaprintsUser()) {
        $this->restore_zp_cookie($id);

        return array('id' => $id,
                     'password' => $customer->getZetaprintsPassword() );
      }
    }

    $credentials = null;

    if ($id = $session->getData('w2puser')) {
      $session->setZetaprintsUser($id);
      $session->setZetaprintsPassword($session->getData('w2ppass'));

      $session->unsetData('w2puser');
      $session->unsetData('w2ppass');
    }

    if ($id = $session->getZetaprintsUser())
      $credentials = array('id' => $id,
                           'password' => $session->getZetaprintsPassword() );
    else
      $credentials = $this->get_credentials_from_zp_cookie();

    if (!$credentials) {
      $id = zetaprints_generate_guid();
      $password = zetaprints_generate_password();

      $url = Mage::getStoreConfig('webtoprint/settings/url');
      $key = Mage::getStoreConfig('webtoprint/settings/key');

      if (zetaprints_register_user($url, $key, $id, $password)) {
        $credentials = array('id' => $id, 'password' => $password);

        $this->set_credentials_to_zp_cookie($credentials);
      }
    } else
      $this->restore_zp_cookie($credentials['id']);

    if (!$credentials)
      return null;

    if ($has_customer) {
      $customer->setZetaprintsUser($credentials['id']);
      $customer->setZetaprintsPassword($credentials['password']);

      $customer->save();
    } else {
      $session->setZetaprintsUser($credentials['id']);
      $session->setZetaprintsPassword($credentials['password']);
    }

    return $credentials;
  }

  function get_credentials_from_zp_cookie () {
    //Get ZetaPrints user id from cookie
    $id = Mage::getSingleton('core/cookie')->get('ZP_ID');

    if (!$id)
      return false;

    //connecting to DB
    $db = Mage::getSingleton('core/resource')->getConnection('core_write');

    //Get password for user from DB
    $password = $db
      ->fetchOne("select pass from zetaprints_cookies where user_id=?",
                 array($id));

    //If there's no password for user in DB then...
    if (strlen($password) != 6) {
      //... remove cookie
      Mage::getSingleton('core/cookie')->delete('ZP_ID');

      return false;
    }

    return array('id' => $id, 'password' => $password);
  }

  function set_credentials_to_zp_cookie ($credentials) {
    Mage::getSingleton('core/cookie')->set('ZP_ID',
                                           $credentials['id'],
                                           self::COOKIE_LIFETIME );

    //connecting to DB
    $db = Mage::getSingleton('core/resource')->getConnection('core_write');
    //adding password to DB
    $db->insert('zetaprints_cookies',
                array('user_id' => $credentials['id'],
                      'pass'=> $credentials['password']) );
  }

  function restore_zp_cookie ($id) {
    Mage::getSingleton('core/cookie')->set('ZP_ID', $id, self::COOKIE_LIFETIME);
  }

  function getCustomOptions ($path = null) {
    return Mage::getSingleton('webtoprint/config')->getOptions($path);
  }

  public function getProfileByName ($name) {
    $collection = Mage::getModel('dataflow/profile')
                    ->getCollection();

    $collection
      ->getSelect()
      ->where('name = ?', $name);

    if ($collection->count())
      return $collection->getFirstItem();

    return null;
  }

  public function getCategory ($name, $createIfNotExists = false,
                               $parent = null) {

    if ($parent && $parent->getId()) {
      foreach ($parent->getChildrenCategories() as $child)
        if ($child->getName() == $name)
          return $child;
    } else {
      $collection = Mage::getModel('catalog/category')
                      ->getCollection()
                      ->addAttributeToFilter('name', $name);

      if ($collection->count())
        return $collection->getFirstItem();
    }

    if (!$createIfNotExists)
      return;

    if ($parent && $parent->getId())
      $rootCategory = $parent;
    else {
      $collection = Mage::getModel('catalog/category')
                      ->getCollection()
                      ->addAttributeToFilter('parent_id', 1);

      if (count($collection) != 1)
        return null;

      $rootCategory = $collection->getFirstItem();

      if (!$rootCategory->getId())
        return null;
    }

    $model = Mage::getModel('catalog/category');

    $model
      ->setStoreId($rootCategory->getStoreId())
      ->setData(array(
                  'name' => $name,
                  'is_active' => 1,
                  'include_in_menu' => 1 ))
      ->setPath($rootCategory->getPath())
      ->setAttributeSetId($model->getDefaultAttributeSetId());

    try {
      $model->save();
    } catch (Exception $e) {
      return null;
    }

    return $model;
  }

}
