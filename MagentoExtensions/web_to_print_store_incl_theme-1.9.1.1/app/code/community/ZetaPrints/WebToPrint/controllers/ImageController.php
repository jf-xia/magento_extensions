<?php

class ZetaPrints_WebToPrint_ImageController
  extends Mage_Core_Controller_Front_Action
  implements ZetaPrints_Api {

  public function indexAction () {
    $this->loadLayout();
    $this->renderLayout();
  }

  public function updateAction () {
    $params = array();
     //Preparing params for image generating request to zetaprints
    foreach ($this->getRequest()->getParams() as $key => $value) {
      if (strpos($key, 'zetaprints-') !== false) {
        $_key = substr($key, 11);
        $_key = substr($_key, 0, 1).str_replace('_', ' ', substr($_key, 1));
        $params[$_key] = str_replace("\n", "\r\n", $value);
      }
    }

    if(count($params) == 0)
      return;

    $user_credentials = Mage::helper('webtoprint')
                          ->get_zetaprints_credentials();

    $params['ID'] = $user_credentials['id'];
    $params['Hash'] = zetaprints_generate_user_password_hash($user_credentials['password']);

    $url = Mage::getStoreConfig('webtoprint/settings/url');
    $key = Mage::getStoreConfig('webtoprint/settings/key');

    echo zetaprints_get_edited_image_url($url, $key, $params);
  }
}
?>
