<?php

class ZetaPrints_WebToPrint_Model_Events_Observer implements ZetaPrints_Api {

  public function create_zetaprints_order ($observer) {
    $quote_item = $observer->getEvent()->getQuoteItem();

    if ($quote_item->getParentItem())
      $quote_item = $quote_item->getParentItem();

    $option_model = $quote_item->getOptionByCode('info_buyRequest');
    $options = unserialize($option_model->getValue());

    if (!(isset($options['zetaprints-TemplateID']) || isset($options['zetaprints-previews'])))
      return;

    if (!(isset($options['zetaprints-TemplateID']) && isset($options['zetaprints-previews'])))
      Mage::throwException('Not enough ZetaPrints template parameters');

    //Use saved order information from the item for M. re-order...
    if (isset($options['zetaprints-order-id'])) {
      //... and mark the item as re-ordered
      $options['zetaprints-reordered'] = true;

      $option_model->setValue(serialize($options));

      return;
    }

    $params = array();

    $params['TemplateID'] = $options['zetaprints-TemplateID'];
    $params['Previews'] = $options['zetaprints-previews'];

    $user_credentials = Mage::helper('webtoprint')
                          ->get_zetaprints_credentials();
    $params['ID'] = $user_credentials['id'];
    $params['Hash'] = zetaprints_generate_user_password_hash($user_credentials['password']);

    $url = Mage::getStoreConfig('webtoprint/settings/url');
    $key = Mage::getStoreConfig('webtoprint/settings/key');

    $order_details = zetaprints_create_order($url, $key, $params);

    if (!$order_details)
      Mage::throwException('ZetaPrints error');

    //We have to show all previews (for dynamic and static pages) on
    //shopping card and order details, so save preview file names for all pages.
    $previews = '';

    foreach ($order_details['template-details']['pages'] as $page) {
      if (isset($page['updated-preview-image']))
        $previews .= ',' . substr($page['updated-preview-image'], 8);
      else
        $previews .= ',' . substr($page['preview-image'], 8);
    }

    $options['zetaprints-previews'] = substr($previews, 1);

    //Save order GUID in the item options
    $options['zetaprints-order-id'] = $order_details['guid'];

    //If order details contain link to low resolution PDF...
    if ($order_details['pdf'] != '')
      //... save it in the item options
      $options['zetaprints-order-lowres-pdf'] = $order_details['pdf'];

    $option_model->setValue(serialize($options));

    Mage::getSingleton('core/session')->unsetData('zetaprints-previews');
  }

  public function store_template_values ($observer) {
    $request = $observer->getEvent()->getControllerAction()->getRequest();

    if (!$request->has('zetaprints-previews'))
      return;

    //Store preview file names in user's session.
    $previews = $request->getParam('zetaprints-previews');
    $previews = serialize(explode(',', $previews));

    Mage::getSingleton('core/session')
                                    ->setData('zetaprints-previews', $previews);

    $user_input = array();
    foreach ($request->getParams() as $key => $value)
      if (strpos($key, 'zetaprints-') !== false) {
        $_key = substr($key, 11);
        $_key = substr($_key, 0, 1).str_replace('_', ' ', substr($_key, 1));
        $user_input['zetaprints-' . $_key] = str_replace("\r\n", "\\r\\n", $value);
      }

    Mage::getSingleton('core/session')->setData('zetaprints-user-input', serialize($user_input));
  }

  public function set_required_options ($observer) {
    $product = $observer->getEvent()->getProduct();

    if ($product->hasWebtoprintTemplate() && $product->getWebtoprintTemplate())
      $product->setRequiredOptions(true);
  }

  public function process_images ($observer) {
    $product = $observer->getEvent()->getProduct();

    if (!$product->hasWebtoprintTemplate()) return;

    $template_guid = $product->getWebtoprintTemplate();
    $template_guid_orig = $product->getOrigData('webtoprint_template');

    if (($template_guid == $template_guid_orig) && !Mage::registry('webtoprint-template-changed'))
     return;

    $media_gallery = $product->getMediaGallery();

    if (is_array($media_gallery))
      foreach ($media_gallery as &$item) {
        if(!is_array($item) && strlen($item) > 0)
          $item = Zend_Json::decode($item);
      }
    else
      $media_gallery = array('images' => array());


    if ($template_guid) {
      $template = Mage::getModel('webtoprint/template')->load($template_guid);

      if (!$template->getId()) return;

      $xml = new SimpleXMLElement($template->getXml());

      unset($template);
    }

    //Trying to remove images which no longer exist in template
    foreach ($media_gallery['images'] as &$image) {
      if (!(isset($image['file'])
          && strpos(basename($image['file']), 'zetaprints_') === 0)) continue;

      if (!$template_guid) {
        $image['removed'] = 1;

        if ($product->getSmallImage() == $image['file'])
          $product->setSmallImage('no_selection');

        if ($product->getThumbnail() == $image['file'])
          $product->setThumbnail('no_selection');

        continue;
      }

      foreach ($xml->Pages[0]->Page as $page) {
        $image_id = basename((string)$page['PreviewImage']);

        if (strpos(basename($image['file']), "zetaprints_{$image_id}"))
          break;

        $image['removed'] = 1;

        if ($product->getSmallImage() == $image['file'])
          $product->setSmallImage('no_selection');

        if ($product->getThumbnail() == $image['file'])
          $product->setThumbnail('no_selection');
      }
    }

    $product->setMediaGallery($media_gallery);

    if (!$template_guid) return;

    $first_image = true;

    foreach ($media_gallery['images'] as $image)
      if (!isset($image['removed']) && $image['disabled'] === 1) {
        $first_image = false;
        break;
      }

    $attributes = $product->getTypeInstance(true)->getSetAttributes($product);
    $gallery_backend = $attributes['media_gallery']->getBackend();
    unset($attributes);

    foreach ($xml->Pages[0]->Page as $page) {
      $image_id = basename((string)$page['PreviewImage']);

      $image_exists = false;
      if (is_array($media_gallery))
        foreach ($media_gallery['images'] as &$image)
          if (!isset($image['removed']) && isset($image['file'])
              && strpos(basename($image['file']), "zetaprints_{$image_id}") === 0)

              $image_exists = true;

      if ($image_exists) break;

      $client = new Varien_Http_Client(
                                 Mage::getStoreConfig('webtoprint/settings/url')
                                 . '/'
                                 . (string)$page['PreviewImage']);

      $filename = Mage::getBaseDir('var') . "/tmp/zetaprints_{$image_id}";

      file_put_contents($filename, $client->request()->getBody());

      unset($client);

      $file = $gallery_backend->addImage($product, $filename, null, true);

      $data = array('label' => (string)$page['Name']);

      if ($first_image) {
        if (!$product->getSmallImage() || $product->getSmallImage() == 'no_selection')
          $product->setSmallImage($file);

        if (!$product->getThumbnail() || $product->getThumbnail() == 'no_selection')
          $product->setThumbnail($file);

        $first_image = false;
      } else
        $data['exclude'] = 0;

      $gallery_backend->updateImage($product, $file, $data);

      unset($data);
    }

    unset($gallery_backend);
    unset($xml);
  }

  public function specify_option_message ($observer) {
    $request = Mage::app()->getRequest();

    if ($request->getParam('options')) {
      $product = $observer->getEvent()->getProduct();

      if ($product->hasWebtoprintTemplate() && $product->getWebtoprintTemplate()) {
        Mage::getSingleton('catalog/session')->addNotice(
          Mage::helper('webtoprint')->__('Please specify the product\'s '
                                . 'required option(s) and/or personalize it') );

        $request->setParam('options', 0);
      }
    }
  }

  public function delete_zetaprints_order ($observer) {
    $order = $observer->getEvent()->getDataObject();

    //Continue only on complete or canceled status of the order
    if (!($order->getState() == Mage_Sales_Model_Order::STATE_COMPLETE
       || $order->getState() == Mage_Sales_Model_Order::STATE_CANCELED))
      return;

    $url = Mage::getStoreConfig('webtoprint/settings/url');
    $key = Mage::getStoreConfig('webtoprint/settings/key');

    //For every item in the order
    foreach ($order->getAllItems() as $item) {
      $options = $item->getProductOptions();

      //check if it's web-to-print product then continue
      if (!isset($options['info_buyRequest']['zetaprints-order-id']))
        continue;

      $order_guid = $options['info_buyRequest']['zetaprints-order-id'];

      //receive current ZetaPrints order status (need it on next step)
      $order_details = zetaprints_get_order_details($url, $key, $order_guid);

      if (!$order_details) continue;

      $old_status = $order_details['status'];

      //change ZetaPrints order status to 'deleted' from current one
      zetaprints_change_order_status($url, $key, $order_guid, $old_status, 'deleted');
    }
  }

  /**
   * Generate url to product with for-item parameter and save it as
   * redirect url in quote item. Redirect url will be used as link to already
   * personalized product on cart page
   */
  public function save_product_url ($observer) {
    $item = $observer->getEvent()->getItem();

    if ($item->getParentItem())
      $item = $item->getParentItem();

    //If redirect URL has already set then exit from the function.
    if ($item->getRedirectUrl())
      return;

    if (! $option_model = $item->getOptionByCode('info_buyRequest'))
      return;

    $options = unserialize($option_model->getValue());

    if (!(isset($options['zetaprints-TemplateID']) || isset($options['zetaprints-previews'])))
      return;

    //Get product model for quote item
    $product = $item->getProduct();
    $option  = $item->getOptionByCode('product_type');
    if ($option)
      $product = $option->getProduct();

    //Generate URL for product with for-item parameter
    $url = Mage::helper('webtoprint')->create_url_for_product($product,
                                    array('for-item' => $item->getId()) );

    //Set generated URL and then save item object
    $item->setRedirectUrl($url)->save();
  }

  public function restore_credentials_in_customer ($observer) {
    //Do not restore credentials if customer's info was updated in admin
    //interface
    if (Mage::app()->getStore()->isAdmin())
      return;

    $session = Mage::getSingleton('customer/session');

    if ($id = $session->getZetaprintsUser())
      $credentials = array('id' => $id,
                           'password' => $session->getZetaprintsPassword() );
    else
      $credentials = Mage::helper('webtoprint')
                       ->get_credentials_from_zp_cookie();

    if ($credentials) {
      $customer = $observer->getEvent()->getDataObject();

      $customer->setZetaprintsUser($credentials['id']);
      $customer->setZetaprintsPassword($credentials['password']);
    }
  }

  public function complete_zetaprints_order ($observer) {
    $order = $observer->getEvent()->getOrder();

    foreach ($order->getAllItems() as $item) {
      $options = $item->getProductOptions();

      if (!isset($options['info_buyRequest']['zetaprints-order-id']))
        continue;

      //If the item was reordered skip it (we can't complete already completed
      //order on ZetaPrints)
      if (isset($options['info_buyRequest']['zetaprints-reordered'])
          && $options['info_buyRequest']['zetaprints-reordered'] === true)
        continue;

      $url = Mage::getStoreConfig('webtoprint/settings/url');
      $key = Mage::getStoreConfig('webtoprint/settings/key');

      //GUID for ZetaPrints order which was saved on Add to cart step
      $current_order_id = $options['info_buyRequest']['zetaprints-order-id'];
      //New GUID for completed order
      $new_order_id = zetaprints_generate_guid();

      $order_details = zetaprints_complete_order($url, $key, $current_order_id,
                                                                 $new_order_id);

      if (!$order_details) {
        //Check if saved order exists on ZetaPrints...
        if (zetaprints_get_order_details($url, $key, $current_order_id)) {
          //... then try again to complete the order
          $order_details = zetaprints_complete_order($url, $key,
                                              $current_order_id, $new_order_id);

          //If it fails...
          if (!$order_details) {
            //... then set state for order in M. as problems and add comment
            $order->setState('problems', true,
                'Use the link to ZP order to troubleshoot.')
              ->save();
            return;
          }
        }
        //... otherwise try to get order details by new GUID and if completed
        //order doesn't exist in ZetaPrints...
        else if (!$order_details =
                       zetaprints_get_order_details($url, $key, $new_order_id)) {
          //... then set state for order in M. as problems and add comment about
          //failed order on ZetaPrints side.
          $order->setState('problems', true,
                  'Failed order. Contact admin@zetaprints.com ASAP to resolve.')
            ->save();

          return;
        }
      }

      $types = array('pdf', 'gif', 'png', 'jpeg', 'cdr');

      foreach ($types as $type)
        if (strlen($order_details[$type]))
          $options['info_buyRequest']['zetaprints-file-'.$type] = $url . '/' . $order_details[$type];

      $options['info_buyRequest']['zetaprints-order-id'] = $order_details['guid'];

      $item->setProductOptions($options)->save();
    }

    return $this;
  }
}

?>
