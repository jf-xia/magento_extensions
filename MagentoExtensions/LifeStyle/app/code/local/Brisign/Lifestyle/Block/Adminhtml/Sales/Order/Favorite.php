<?php

/**
 * Lifestyle Block
 *
 * @category	Brisign
 * @package		Brisign_Lifestyle
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Brisign_Lifestyle_Block_Adminhtml_Sales_Order_Favorite extends Mage_Adminhtml_Block_Template
{
    private $_favorite;

	/**
	 * Based on the object being viewed i.e. order, invoice etc then 
	 * lets get the favorite from the order if available
	 * 
	 * @return void
	*/
    private function _initFavorite()
    {
		$favoriteId = '';
		
        if (! is_null(Mage::registry('current_order'))) {
            $favoriteId = Mage::registry('current_order')->getData('lifestyle_favorite_id');
        }
        elseif(! is_null(Mage::registry('current_shipment'))) {
            $favoriteId = Mage::registry('current_shipment')->getOrder()->getData('lifestyle_favorite_id');  
        }
        elseif(! is_null(Mage::registry('current_invoice'))) {
            $favoriteId = Mage::registry('current_invoice')->getOrder()->getData('lifestyle_favorite_id'); 
        }
		elseif(! is_null(Mage::registry('current_creditmemo'))) {
			$favoriteId = Mage::registry('current_creditmemo')->getOrder()->getData('lifestyle_favorite_id'); 
		}
		
		if ($favoriteId != '') {
			$this->_favorite = Mage::getModel('lifestyle/favorite')->load($favoriteId)->getFavorite();
		}
    }

	/**
	 * Initialise the lifestyle instruction and return
	 *
	 * @return mixed bool|string
	*/
    protected function getFavorite()
    {
       if (is_null($this->_favorite)) {
            $this->_initFavorite();
       }
	   return empty($this->_favorite) ? false : $this->_favorite;
    }
}