<?php

class Wee_Fpc_Helper_Product_Compare extends Mage_Catalog_Helper_Product_Compare
{
   public function setItemCollection(array $items)
   {
       $this->_itemCollection = $items;
   }

   public function getItemsFromSession()
   {
        $items = array();
        $customerSession = Mage::getSingleton('customer/session');
        $itemsFromSession = (array)$customerSession->getComparedProducts();
        foreach ($itemsFromSession as $itemId) {
            $product = Mage::getModel('catalog/product')->load($itemId);
            if ($product->getId()) {
                $items[$product->getId()] = $product;
            }
        }
        return $items;
   }
}