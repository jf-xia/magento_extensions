<?php

/**
 * Checklist Block Checknote
 *
 * @category	Brisign
 * @package		Brisign_Checklist
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Brisign_Checklist_Block_Checknote extends Mage_Core_Block_Template
{
	/**
	 * If the character count is enabled in the configs then return 
	 * the maximum characters allowed
	 *
	 * @return mixed int|bool
	*/
	public function getCharacterCount()
	{
		if (Mage::getStoreConfig('checklist_options/character_count/enabled')) {
			return Mage::getStoreConfig('checklist_options/character_count/maximum_characters');
		}
		return false;
	}
	
	/**
	 * No point in showing the checklist checknote box if there are no shipping 
	 * methods available
	 *
	 * @return bool
	*/
	public function canShow()
	{
		if (count(Mage::getSingleton('customer/session')->getCustomer()->getId())) {
			return true;
		}
		return false;
	}
}