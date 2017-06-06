<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * Used to override Mage_Checkout_Block_Cart_Crosssell
 * 
 * Automatically replaces the functionality to get cross sell products
 * 
 */
class FourTell_Recommend_Block_Checkout_Cart_Crosssellcommunity extends Mage_Checkout_Block_Cart_Crosssell 
{
    public function getItemCollection()
    {
		return $this->getItems();
	}
	
	public function getItems()
	{
		$items = array();

    	// If extension is not enabled use default functionality
		if (Mage::getStoreConfig('recommend/config/enabled') != "1")
			return parent::getItems();
			
		// Load the 4-Tell web service class
		require_once("FourTell/Recommend/Block/Recommend.php");
		$rec = new Recommender();
		
		// Call the web service to get recommended products
		$ids = 	$rec->getRecommendations(
						implode(",", Mage::helper('checkout/cart')->getCart()->getProductIds()),
						"",
						"",
						Mage::getSingleton('customer/session')->getCustomerId(),
						"Crosssell",
						Mage::getStoreConfig('recommend/display_recommendation/numcrosssell')
				);

		// Load each product and add to the collection
		foreach($ids as $id) {
			$items[] = Mage::getModel('catalog/product')->load($id);
		}

		return $items;
	}
}
