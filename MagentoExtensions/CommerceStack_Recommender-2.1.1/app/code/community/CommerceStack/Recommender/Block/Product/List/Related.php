<?php

class CommerceStack_Recommender_Block_Product_List_Related extends Mage_Catalog_Block_Product_List_Related
{
    protected function _prepareData()
    {
        /* @var $product Mage_Catalog_Model_Product */
        $product = Mage::registry('product');
        
        // A bit of a hack, but return an empty collection if user selected 0 recommendations to show in config
        $limit = Mage::getStoreConfig('recommender/relatedproducts/numberofrelatedproducts');
        if($limit < 1)
        {
            $this->_itemCollection = $product->getRelatedProductCollection();
            $this->_itemCollection->load();
            $this->_itemCollection->clear();
            return $this;
        }

        // Get manual links
        // Set link source to automated CommerceStack recommendations
        $linkModel = $product->getLinkInstance();
        $linkModel->useLinkSourceManual();
        parent::_prepareData();
        $unionLinkedItemCollection = $this->_itemCollection;

        $numRecsToGet = $limit;
        if(!is_null($unionLinkedItemCollection))
        {
            $numRecsToGet = $limit - count($unionLinkedItemCollection);
        }

        if($numRecsToGet > 0)
        {
            // Figure out if we should use a category filter
            $constrainCategory = Mage::getStoreConfig('recommender/relatedproducts/constraincategory');
            $currentCategory = Mage::registry('current_category');
            $productCategory = $currentCategory;
            if(is_null($currentCategory))
            {
                // This could be a recently viewed or a search page. Try to get category collection and arbitrarily use first
                /* @var $currentProduct Mage_Catalog_Model_Product */
                $currentProduct = Mage::registry('current_product');
                if (is_object($currentProduct))
                {
                    $currentCategory = $currentProduct->getCategoryCollection();
                    $productCategory = $currentCategory->getFirstItem();
                    $currentCategory = $productCategory;
                }
            }
            $useCategoryFilter = !is_null($currentCategory) && $constrainCategory;

            // Set link source to automated CommerceStack recommendations
            $linkModel = $product->getLinkInstance();
            $linkModel->useLinkSourceCommerceStack();
        }

        while($numRecsToGet > 0)
        {
            $linkedItemCollection = $product->getRelatedProductCollection()
                ->addAttributeToSelect('required_options')
                ->setGroupBy()
                ->setPositionOrder()
                ->addStoreFilter();

            $linkedItemCollection->getSelect()->limit($numRecsToGet);

            if($useCategoryFilter)
            {
                $linkedItemCollection->addCategoryFilter($currentCategory);
            }

            if(!is_null($unionLinkedItemCollection))
            {
                $linkedItemCollection->addExcludeProductFilter($unionLinkedItemCollection->getAllIds());
            }

            Mage::getResourceSingleton('checkout/cart')->addExcludeProductFilter($linkedItemCollection,
                Mage::getSingleton('checkout/session')->getQuoteId()
            );
            $this->_addProductAttributesAndPrices($linkedItemCollection);

            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($linkedItemCollection);

            $linkedItemCollection->load();

            // Add new source linked items to existing union of linked items
            if(is_null($unionLinkedItemCollection))
            {
                $unionLinkedItemCollection = $linkedItemCollection;
            }
            else
            {
                foreach($linkedItemCollection as $linkedProduct)
                {
                    $unionLinkedItemCollection->addItem($linkedProduct);
                }
            }

            if(!is_null($unionLinkedItemCollection))
            {
                $numRecsToGet = $limit - count($unionLinkedItemCollection);
            }

            if(!$useCategoryFilter) break;

            // Go up a category level for next iteration
            $currentCategory = $currentCategory->getParentCategory();
            if(is_null($currentCategory->getId())) break;
        }

        // If we still don't have enough recommendations fill out the remaining with randoms.
        if($numRecsToGet > 0) $currentCategory = $productCategory;
        while($numRecsToGet > 0)
        {
            $randCollection = Mage::getResourceModel('catalog/product_collection');
            Mage::getModel('catalog/layer')->prepareProductCollection($randCollection);
            $randCollection->getSelect()->order('rand()');
            $randCollection->addStoreFilter();
            $randCollection->setPage(1, $numRecsToGet);
            $randCollection->addIdFilter(array_merge($unionLinkedItemCollection->getAllIds(), array($product->getId())), true);

            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($randCollection);

            if($useCategoryFilter)
            {
                $randCollection->addCategoryFilter($currentCategory);
            }
            
            foreach($randCollection as $linkedProduct)
            {
                $unionLinkedItemCollection->addItem($linkedProduct);
            }
            
            if(!$useCategoryFilter) break; // We tried everything

            if(!is_null($unionLinkedItemCollection))
            {
                $numRecsToGet = $limit - count($unionLinkedItemCollection);
            }
            
            // Go up a category level for next iteration
            $currentCategory = $currentCategory->getParentCategory();
            if(is_null($currentCategory->getId())) break;
            
        }
        
        $this->_itemCollection = $unionLinkedItemCollection;
        
        
        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }

        return $this;
    }
}