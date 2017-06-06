<?php

/**
 * Lifestyle Favorite Observer Model
 *
 * @category	Brisign
 * @package		Brisign_Lifestyle
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Brisign_Lifestyle_Model_Observer extends Mage_Core_Helper_Abstract
{        
	/**
	 * Take the favorite from post and and store it in the current quote.
	 * 
	 * When the quote gets converted we will store the lifestyle favorite
	 * and assign to the order
	 *
	 * @param Varien_Object $observer
	 * @return Brisign_Lifestyle_Model_Observer
	*/
    public function checkoutEventCreateLifestyleFavorite($observer)
    {
        $favorite = $observer->getEvent()->getRequest()->getParam('lifestyle-favorite');
        
		if (! empty($favorite)) {		
			$observer->getEvent()->getQuote()->setLifestyleFavorite((string)$favorite)->save();
		}
        return $this;
    }
    
	/**
	 * If the quote has a lifestyle favorite then lets save that favorite and 
	 * assign the id to the order
	 * 
	 * @param Varien_Object $observer
	 * @return Brisign_Lifestyle_Model_Observer
	*/
    public function salesEventConvertQuoteToOrder($observer)
    {
		if ($favorite = $observer->getEvent()->getQuote()->getLifestyleFavorite()) {		
			$lifestyleFavorite = Mage::getModel('lifestyle/favorite')->setFavorite($favorite)->save();
			
			$observer->getEvent()->getOrder()
				->setLifestyleFavoriteId($lifestyleFavorite->getLifestyleFavoriteId());
		}
        return $this;
    }
}