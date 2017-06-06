<?php

class Magebuzz_Catsidebarnav_Helper_Category extends Mage_Catalog_Helper_Category
{
	public function getAllCategories($sorted=false, $asCollection=false, $toLoad=true)
    {
        $parent     = Mage::app()->getStore()->getRootCategoryId();
        $cacheKey   = sprintf('%d-%d-%d-%d', $parent, $sorted, $asCollection, $toLoad);
        if (isset($this->_storeCategories[$cacheKey])) {
            return $this->_storeCategories[$cacheKey];
        }

        /**
         * Check if parent node of the store still exists
         */
        $category = Mage::getModel('catalog/category');
        /* @var $category Mage_Catalog_Model_Category */
        if (!$category->checkId($parent)) {
            if ($asCollection) {
                return new Varien_Data_Collection();
            }
            return array();
        }

        $categoryDepth  = max(0, (int)Mage::getStoreConfig('catsidebarnav/display_settings/category_level'));
        $storeCategories = $category->getCategories($parent, $categoryDepth, $sorted, $asCollection, $toLoad);

        $this->_storeCategories[$cacheKey] = $storeCategories;
        return $storeCategories;
    }
}