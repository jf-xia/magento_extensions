<?php

class ZetaPrints_WebToPrint_Block_Catalog_Product_Edit_Tab_Templates_Radiobutton extends Mage_Adminhtml_Block_Widget {
  public function __construct() {
    parent::__construct();
    $this->setTemplate('catalog/product/tab/templates/radiobutton.phtml');
  }

  public function get_product () {
    return Mage::registry('product');
  }
}

?>
