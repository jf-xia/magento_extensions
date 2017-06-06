<?php

class Thirty4_CatalogSale_Block_Widget_List
  extends Thirty4_CatalogSale_Block_List
  implements Mage_Widget_Block_Interface
{
  public function getProductsCount()
  {
    if (!$this->hasData('products_count')) {
      return parent::getProductsCount();
    }
    return $this->_getData('products_count');
  }

}