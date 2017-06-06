<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * Used to override Mage_Catalog_Block_Product_List_Related
 * 
 * Automatically replaces the functionality to get related products
 * 
 */
class FourTell_Recommend_Block_Catalog_Product_List_Related extends Mage_Catalog_Block_Product_List_Related
{
    protected $_itemCollection;

    protected function _prepareData()
    {
    	// If extension is not enabled use default functionality
		if (Mage::getStoreConfig('recommend/config/enabled') != "1")
			return parent::_prepareData();
		
    	// If related recommendations is off the use default functionality
    	if (!Mage::getStoreConfig('recommend/display_recommendation/related'))
			return parent::_prepareData();

		if (!isset($GLOBALS['previous_products'])) {
			$GLOBALS['previous_products'] = array();
		}

		// Get current product being viewed
        $product = Mage::registry('product');

		// Load the 4-Tell web service class
		require_once("FourTell/Recommend/Block/Recommend.php");
		$rec = new Recommender();
		
		// Call the web service to get recommended products
		$ids = 	$rec->getRecommendations(
						$product->getData('entity_id'), 
						implode(",", Mage::helper('checkout/cart')->getCart()->getProductIds()),
						implode(",", $GLOBALS['previous_products']),
						Mage::getSingleton('customer/session')->getCustomerId(),
						"Related",
						Mage::getStoreConfig('recommend/display_recommendation/numrelated')
				);
		
		if (!isset($GLOBALS['previous_products']) || empty($GLOBALS['previous_products'])) {
			$GLOBALS['previous_products'] = $ids;
		}
		
		$this->_itemCollection = Mage::getModel('catalog/product')->getCollection()
				->addAttributeToFilter('entity_id', array(
    				'in' => $ids,
				));
		
        Mage::getResourceSingleton('checkout/cart')->addExcludeProductFilter($this->_itemCollection,
            Mage::getSingleton('checkout/session')->getQuoteId()
        );
        $this->_addProductAttributesAndPrices($this->_itemCollection);

        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($this->_itemCollection);

        $this->_itemCollection->load();

        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
		
        return $this;
    }

    protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    public function getItems()
    {
        return $this->_itemCollection;
    }

	public function hasItems()
	{
		return True;
	}

	public function getItemCollection()
	{
		return $this->_itemCollection;
	}
}
