<?php

/**
 * Deliverydate Block date
 *
 * @category	Dh
 * @package		Dh_Deliverydate
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Dh_Deliverydate_Block_date extends Mage_Core_Block_Template
{
	/**
	 * If the character count is enabled in the configs then return 
	 * the maximum characters allowed
	 *
	 * @return mixed int|bool
	*/
	public function getCharacterCount()
	{
		if (Mage::getStoreConfig('deliverydate_options/character_count/enabled')) {
			return Mage::getStoreConfig('deliverydate_options/character_count/maximum_characters');
		}
		return false;
	}
	
	/**
	 * No point in showing the delivery date box if there are no shipping 
	 * methods available
	 *
	 * @return bool
	*/
	public function canShow()
	{
		if (count(Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getShippingRatesCollection())) {
			return true;
		}
		return false;
	}
}