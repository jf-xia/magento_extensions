<?php

class CommerceStack_Recommender_Block_Cart_Crosssell extends Mage_Checkout_Block_Cart_Crosssell
{
    protected $_linkSource = array('useLinkSourceManual', 'useLinkSourceCommerceStack'); // from most to least authoritative

    protected function _getCollection($linkSource = null)
    {
        if(is_null($linkSource)) $linkSource = $this->_linkSource[0];

        $collection = Mage::getModel('catalog/product_link')->useCrossSellLinks()
            ->{$linkSource}()
            ->getProductCollection()
            ->setStoreId(Mage::app()->getStore()->getId())
            ->addStoreFilter();
        $this->_addProductAttributesAndPrices($collection);

        Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);

        return $collection;
    }

    public function getItems()
    {
        $items = $this->getData('items');
        if (is_null($items)) {
            $items = array();
            $ninProductIds = $this->_getCartProductIds();
            if ($ninProductIds) {
                $lastAdded = (int) $this->_getLastAddedProductId();
                if ($lastAdded) {
                    $collection = $this->_getCollection()
                        ->addProductFilter($lastAdded);
                    if (!empty($ninProductIds)) {
                        $collection->addExcludeProductFilter($ninProductIds);
                    }
                    $collection->setPositionOrder()->load();

                    foreach ($collection as $item) {
                        $ninProductIds[] = $item->getId();
                        $items[] = $item;
                    }
                }

                $limit = Mage::getStoreConfig('recommender/relatedproducts/numberofcrosssellproducts') - count($items);

                // A bit of a hack, but return an empty collection if user selected 0 recommendations to show in config
                if($limit < 1)
                {
                    /*$this->_itemCollection = $this->_getCollection($this->_linkSource[0]);
                    $this->_itemCollection->load();
                    $this->_itemCollection->clear();
                    return $this;*/
                    $this->setData('items', $items);
                    return $items;
                }

                if (count($items) > $limit)
                {
                    $this->setData('items', $items);
                    return $items;
                }

                $unionLinkedItemCollection = null;
                foreach($this->_linkSource as $linkSource)
                {
                    $numRecsToGet = $limit;
                    if(!is_null($unionLinkedItemCollection))
                    {
                        $numRecsToGet = $limit - count($unionLinkedItemCollection);
                    }

                    if($numRecsToGet > 0)
                    {
                        if (count($items) < $numRecsToGet)
                        {
                            if(!is_null($unionLinkedItemCollection))
                            {
                                $ninProductIds = array_merge($ninProductIds, $unionLinkedItemCollection->getAllIds());
                            }

                            $filterProductIds = array_merge($this->_getCartProductIds(), $this->_getCartProductIdsRel());
                            $collection = $this->_getCollection($linkSource)
                                ->addProductFilter($filterProductIds)
                                ->addExcludeProductFilter($ninProductIds)
                                ->setGroupBy()
                                ->setPositionOrder();

                            $collection->getSelect()->limit($numRecsToGet);
                            $collection->load();
                        }

                        if(is_null($unionLinkedItemCollection))
                        {
                            $unionLinkedItemCollection = $collection;
                        }
                        else
                        {
                            // Add new source linked items to existing union of linked items
                            foreach($collection as $linkedProduct)
                            {
                                $unionLinkedItemCollection->addItem($linkedProduct);
                            }
                        }
                    }
                }
            }


            if(@count($unionLinkedItemCollection) < $limit)
            {
                // Get categories for randoms
                $cartProducts = $this->getQuote()->getAllItems();
                $firstProduct = $cartProducts[0];
                $firstProduct = $firstProduct->getProduct();
                $category = $firstProduct->getCategoryCollection();
                $category = $category->getFirstItem(); // Arbitrary. Really we should do all items before moving up the hierarchy

                $constrainCategory = Mage::getStoreConfig('recommender/relatedproducts/constraincategory');
                $useCategoryFilter = !is_null($category) && $constrainCategory;
            }

            while(@count($unionLinkedItemCollection) < $limit)
            {
                // We still don't have enough recommendations. Fill out the remaining with randoms.
                $numRecsToGet = $limit - count($unionLinkedItemCollection);

                $randCollection = Mage::getResourceModel('catalog/product_collection');
                Mage::getModel('catalog/layer')->prepareProductCollection($randCollection);
                $randCollection->getSelect()->order('rand()');
                $randCollection->addStoreFilter();
                $randCollection->setPage(1, $numRecsToGet);
                $randCollection->addIdFilter(array_merge($unionLinkedItemCollection->getAllIds(), $this->_getCartProductIds()), true);

                Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($randCollection);

                if($useCategoryFilter)
                {
                    $randCollection->addCategoryFilter($category);
                }

                foreach($randCollection as $linkedProduct)
                {
                    $unionLinkedItemCollection->addItem($linkedProduct);
                }

                if(!$useCategoryFilter) break; // We tried everything

                // Go up a category level for next iteration
                $category = $category->getParentCategory();
                if(is_null($category->getId())) $useCategoryFilter = false;
            }

            foreach(@$unionLinkedItemCollection as $item)
            {
                $items[] = $item;
            }

            $this->setData('items', $items);
        }
        return $items;
    }
}