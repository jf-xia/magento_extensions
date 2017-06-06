<?php

class Thirty4_CatalogSale_Block_Layer extends Mage_Catalog_Block_Layer_View
{
  public function getLayer()
  {
    return Mage::getSingleton('catalogsale/layer');
  }
}