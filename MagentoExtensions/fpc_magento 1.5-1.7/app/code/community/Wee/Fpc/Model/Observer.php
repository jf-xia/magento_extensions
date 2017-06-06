<?php
class Wee_Fpc_Model_Observer
{
    public function processPostDispatch(Varien_Event_Observer $observer)
    {
        Mage::getModel('wee_fpc/fullpagecache')->save();
        return $this;
    }

    public function addCompareProduct(Varien_Event_Observer $observer)
    {
        $product = $observer->getProduct();
        $productId = $product->getId();
        if ($productId) {
          $customerSession = Mage::getSingleton('customer/session');
          $comparedProducts = (array)$customerSession->getComparedProducts();
          if (!isset($comparedProducts[$productId])) {
              $comparedProducts[$productId] = $productId;
          }
          $customerSession->setComparedProducts($comparedProducts);
        }
    }

    public function removeCompareProduct(Varien_Event_Observer $observer)
    {
        $product = $observer->getProduct();
        $productId = $product->getProductId();
        if ($productId) {
          $customerSession = Mage::getSingleton('customer/session');
          $comparedProducts = (array)$customerSession->getComparedProducts();
          if (isset($comparedProducts[$productId])) {
              unset($comparedProducts[$productId]);
          }
          $customerSession->setComparedProducts($comparedProducts);
        }
    }

    public function clearCompareProducts()
    {
        Mage::getSingleton('customer/session')->unsetData('compared_products');
    }
    
    public function catalogProductView(Varien_Event_Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        Mage::getSingleton('wee_fpc/productViewed')->addProduct($product->getId());
    }
	
}