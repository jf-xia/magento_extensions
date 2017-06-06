<?php

class Wee_Fpc_Model_ProductViewed extends Mage_Core_Model_Session_Abstract
{
    const XML_PATH_RECENTLY_VIEWED_COUNT = 'catalog/recently_products/viewed_count';

    public function __construct()
    {
        $namespace = 'product_viewed';
        $this->init($namespace);
    }
     
    public function addProduct($productId)
    {
        $productViewed = (array)$this->getProductViewed();

        if (!in_array($productId, $productViewed)) {
             array_unshift($productViewed, $productId);
        } else {
            $key = array_search($productId, $productViewed);
            unset($productViewed[$key]);
            array_unshift($productViewed, $productId);
        }

        $maxRecentlyViewedProducts = $this->getMaxRecentlyViewedProducts();
        if (count($productViewed) > $maxRecentlyViewedProducts) {
            $productViewed = array_slice($productViewed, 0, $maxRecentlyViewedProducts);
        }

        $this->setData('product_viewed', $productViewed);
    }
     
    public function getMaxRecentlyViewedProducts()
    {
        return (int)Mage::getStoreConfig(self::XML_PATH_RECENTLY_VIEWED_COUNT);
    }
    
    public function getProductViewed()
    {
        return (array)$this->getData('product_viewed');
    }
}