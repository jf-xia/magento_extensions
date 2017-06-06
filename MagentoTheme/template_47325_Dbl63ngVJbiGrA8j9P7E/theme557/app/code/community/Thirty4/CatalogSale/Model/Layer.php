<?php

class Thirty4_CatalogSale_Model_Layer extends Mage_Catalog_Model_Layer
{
  protected function _getStoreId()
  {
    $storeId = Mage::app()->getStore()->getId();
    return $storeId;
  }

  protected function _getCustomerGroupId()
  {
    $custGroupID = null;
    if($custGroupID == null) {
      $custGroupID = Mage::getSingleton('customer/session')->getCustomerGroupId();
    }
    return $custGroupID;
  }
  public function getProductCollection()
  {
    if (is_null($this->_productCollection)) {
      $storeId = $this->_getStoreId();
      $websiteId = Mage::app()->getStore($storeId)->getWebsiteId();

      $custGroup = $this->_getCustomerGroupId();
      $product = Mage::getModel('catalog/product');
      $todayDate = $product->getResource()->formatDate(time(), false);
      $rulePriceWhere = "({{table}}.rule_date is null) or ({{table}}.rule_date='$todayDate' and {{table}}.website_id='$websiteId' and {{table}}.customer_group_id='$custGroup')";

      $specials = $product->setStoreId($storeId)->getResourceCollection()
      ->addAttributeToFilter('special_price', array('gt'=>0), 'left')
      ->addAttributeToFilter('special_from_date', array('date'=>true, 'to'=> $todayDate), 'left')
      ->addAttributeToFilter(array(
      array('attribute'=>'special_to_date', 'date'=>true, 'from'=>$todayDate),
      array('attribute'=>'special_to_date', 'is' => new Zend_Db_Expr('null'))
      ), '', 'left')
      ->addAttributeToSort('special_from_date', 'desc')
      ->joinTable('catalogrule/rule_product_price', 'product_id=entity_id', array('rule_price'=>'rule_price', 'rule_start_date'=>'latest_start_date', 'rule_date'=>'rule_date'), $rulePriceWhere, 'left')
      ;

      $rulePriceCollection = Mage::getResourceModel('catalogrule/rule_product_price_collection')
      ->addFieldToFilter('website_id', $websiteId)
      ->addFieldToFilter('customer_group_id', $custGroup)
      ->addFieldToFilter('rule_date', $todayDate)
      ;

      $productIds = $rulePriceCollection->getProductIds();

      if (!empty($productIds)) {
        $specials->getSelect()->orWhere('e.entity_id in ('.implode(',',$productIds).')');
      }

      Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($specials);
      Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($specials);

      $this->prepareProductCollection($specials);

      $this->_productCollection = $specials;
    }

    return $this->_productCollection;
  }
}

