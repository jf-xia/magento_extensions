<?php

/**
 * Checklist helper
 *
 * @category	Brisign
 * @package		Brisign_Checklist
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Brisign_Checklist_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Return the front end label as defined in config
	 *
	 * @return string
	*/
	public function getFrontendLabel()
	{
		return Mage::getStoreConfig('checklist_options/basic_settings/frontend_label');
	}
}