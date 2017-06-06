<?php

class ZetaPrints_WebToPrint_Block_Catalog_Product_Edit_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs {
  protected function _prepareLayout () {
    $ret = parent::_prepareLayout();

    if ($this->getProduct()->getAttributeSetId() || $this->getRequest()->getParam('set', null))
      $this->addTab('templates', array(
        'label' => Mage::helper('catalog')->__('Web-to-print templates'),
        'url'       => $this->getUrl('web-to-print/catalog_product/templates', array('_current' => true)),
        'class'     => 'ajax' ));

    return $ret;
  }
}

?>
