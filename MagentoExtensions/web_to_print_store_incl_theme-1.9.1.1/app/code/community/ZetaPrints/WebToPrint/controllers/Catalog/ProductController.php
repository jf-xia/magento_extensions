<?php

require_once 'Mage/Adminhtml/controllers/Catalog/ProductController.php';

class ZetaPrints_WebToPrint_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController {
  public function templatesGridAction() {
    $this->_initProduct();
    $this->loadLayout();

    $this->getResponse()->setBody(
        $this->getLayout()
          ->createBlock('webtoprint/catalog_product_edit_tab_templates')
          ->toHtml() );
  }

  public function templatesAction () {
    $this->_initProduct();

    $radio_block= $this->getLayout()
      ->createBlock('webtoprint/catalog_product_edit_tab_templates_radiobutton');

    $grid_block = $this->getLayout()
      ->createBlock('webtoprint/catalog_product_edit_tab_templates')
      ->setGridUrl($this->getUrl('*/*/templatesGrid', array('_current' => true)));

    $this->_outputBlocks($radio_block, $grid_block);
  }

  public function updateProfileAction () {
    $profileId = $this->getRequest()->getParam('profile-id', null);
    $productId = $this->getRequest()->getParam('product-id');

    if (!$profileId) {
      $this->_redirect('adminhtml/catalog_product/edit',
                       array('id' => $productId) );
      return;
    }

    $profile = Mage::getModel('dataflow/profile')->load($profileId);

    if (!$profile->getId()) {
      $this->_redirect('adminhtml/catalog_product/edit',
                       array('id' => $productId) );
      return;
    }

    $actionsXml
            = simplexml_load_string("<data>{$profile->getActionsXml()}</data>");

    if ($actionsXml) {
      $actionsXml->action[0]['source-product-id'] = $productId;

      $profile
        ->setData('actions_xml', $actionsXml->action->asXml())
        ->save();
    }

    $this->_redirect('adminhtml/system_convert_profile/edit',
                     array('id' => $profile->getId()));
  }

}

?>
