<?php

/**
 * Deliverynote Block Note
 *
 * @category	Dh
 * @package		Dh_Deliverynote
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Dh_Deliverynote_Block_Note extends Mage_Core_Block_Template
{
	/**
	 * If the character count is enabled in the configs then return 
	 * the maximum characters allowed
	 *
	 * @return mixed int|bool
	*/
	public function getCharacterCount()
	{
		if (Mage::getStoreConfig('deliverynote_options/character_count/enabled')) {
			return Mage::getStoreConfig('deliverynote_options/character_count/maximum_characters');
		}
		return false;
	}
	
	/**
	 * No point in showing the delivery note box if there are no shipping 
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