<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G. 
 */
class Soon_AdvancedCache_Model_Clean extends Soon_AdvancedCache_Model_Abstract {

    /**
     * Cache tags to clean
     * 
     * @var array
     */
    protected $_tagsToClean = array();

    /**
     * Clean caches
     * 
     * @param mixed string|object $block
     * @param Varien_Event_Observer $observer
     * @return Soon_AdvancedCache_Model_Clean
     */
    public function cleanBlockCache($block, $observer = null) {

        // If the $block param is an object we must generate tag
        if (is_object($block)) {

            // CMS Pages
            if (($block instanceof Mage_Cms_Model_Page) === true) {
                $this->_tagsToClean[] = Mage_Cms_Model_Page::CACHE_TAG . '_' . $block->getId();
                $this->_tagsToClean[] = 'breadcrumbs';
            }

            // Products lists
            if (($block instanceof Mage_Catalog_Model_Category) === true) {
                if ($observer) {
                    $category = $observer->getEvent()->getCategory();
                } else {
                    $category = $block;
                }
                $this->_tagsToClean[] = Mage_Catalog_Model_Category::CACHE_TAG . '_' . $category->getId();
                $this->_tagsToClean[] = 'breadcrumbs';
            }

            // Product's products lists. When a product has changed, we want to update its categories
            if (($block instanceof Mage_Catalog_Model_Product) === true) {
                if ($observer) {
                    $product = $observer->getEvent()->getProduct();
                } else {
                    $product = $block;
                }
                $categoryIds = $product->getCategoryIds();
                foreach ($categoryIds as $categoryId) {
                    $this->_tagsToClean[] = Mage_Catalog_Model_Category::CACHE_TAG . '_' . $categoryId;
                }
            }
        }

        // Else we have a tag as param
        else {
            $this->_tagsToClean[] = $block;
        }

        // We gather all tags to be cleaned for the current projet
        $projectTagsToClean = Mage::getModel('advancedcache/project_clean')->getProjectTagsToClean($block, $observer);
        if (!is_array($projectTagsToClean)) {
            Mage::throwException(Mage::helper('advancedcache')->__('Unable to empty cache project specific blocks. Please make sure they are returned as an array.'));
        }

        // We finally clean all caches
        $allTagsToClean = array_unique(array_merge($this->_tagsToClean, $projectTagsToClean));
        $this->_tagsToClean = $allTagsToClean;

        Mage::dispatchEvent('advancedcache_clean', array('tags' => $this->_tagsToClean));

        Mage::app()->cleanCache($this->_tagsToClean);

        return $this;
    }

    /**
     * Clean cache on observer event
     * 
     * @param object Varien_Event_Observer $observer
     * @return Soon_AdvancedCache_Model_Clean
     */
    public function cleanBlockCacheOnEvent(Varien_Event_Observer $observer) {
        $block = $observer->getEvent()->getDataObject();
        $this->cleanBlockCache($block, $observer);
        
        return $this;
    }

    /**
     * Clean products and categories cache on product mass update
     * 
     * @param Varien_Event_Observer $observer
     * @return Soon_AdvancedCache_Model_Clean
     */
    public function cleanCacheOnMassAttributeUpdate($observer) {
        $productIds = Mage::helper('adminhtml/catalog_product_edit_action_attribute')->getProductIds();
        $this->cleanProductsCache($productIds);
    }

    /**
     * Clean products and categories cache on product status mass update
     * 
     * @param Varien_Event_Observer $observer
     * @return Soon_AdvancedCache_Model_Clean
     */
    public function cleanCacheOnMassStatusUpdate($observer) {
        $productIds = $observer->getEvent()->getProductIds();
        $this->cleanProductsCache($productIds);
        return $this;
    }

    /**
     * Products and products' categories cache cleaner
     * 
     * @param array $productIds
     * @param bool $reindex = false
     * @return Soon_AdvancedCache_Model_Clean
     */
    protected function cleanProductsCache($productIds, $reindex = false) {
        foreach ($productIds as $productId) {
            $this->_tagsToClean[] = Mage_Catalog_Model_Product::CACHE_TAG . '_' . $productId;
            $product = Mage::getModel('catalog/product')->load($productId);

            /**
             * Due to potentially long processing of reindexing, the code below
             * has been commented.
             */
            /*if ($reindex) {
                Mage::getSingleton('index/indexer')->processEntityAction(
                        $product, Mage_Catalog_Model_Product::ENTITY, Mage_Index_Model_Event::TYPE_SAVE
                );
            }*/

            $categoryIds = $product->getCategoryIds();
            foreach ($categoryIds as $categoryId) {
                $this->_tagsToClean[] = Mage_Catalog_Model_Category::CACHE_TAG . '_' . $categoryId;
            }
        }

        $this->_tagsToClean = array_unique($this->_tagsToClean);
        Mage::app()->cleanCache($this->_tagsToClean);
        return $this;
    }

}
