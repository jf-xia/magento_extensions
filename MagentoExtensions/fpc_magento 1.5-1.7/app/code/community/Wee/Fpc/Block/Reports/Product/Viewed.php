<?php

class Wee_Fpc_Block_Reports_Product_Viewed extends Mage_Reports_Block_Product_Viewed
{
    const PRODUCT_CONTROLLER_NAME = 'product';
    
    protected $_collection;

    public function getCount()
    {
        return count($this->getItemsCollection());
    }
    
    public function getItemsCollection()
    {
        if (!$this->_collection) {
            $this->_collection = $this->_getItemCollection();
        }
        return $this->_collection;
    }
    
    protected function _getItemCollection()
    {
        $productIds = Mage::getSingleton('wee_fpc/productViewed')->getProductViewed();
        $itemCollection = array();
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addIdFilter($productIds);
        $collection->addAttributeToSelect('name');
        $collection = $collection->load();
        $activeProductId = $this->_getActiveProductId();
        foreach ($productIds as $productId) {
            foreach ($collection as $product) {
                if ($productId != $activeProductId && $productId == $product->getId()) {
                    $itemCollection[] = $product;
                    break;
                }
            }
        }
        return $itemCollection;
    }
    
    protected function _getActiveProductId()
    {
        $request = Mage::app()->getRequest();
        $controllerName = $request->getControllerName();
        $requestProductId = $request->getParam('id');
        $productId = ($requestProductId && $controllerName == self::PRODUCT_CONTROLLER_NAME) ? $requestProductId : $this->getActiveProductId();
        return $productId;
    }
    
    protected function _toHtml()
    {
        if (!$this->getCount()) {
            return '<!--product_viewed_start--><!--product_viewed_end-->';
        }
        return parent::_toHtml();
    }
}