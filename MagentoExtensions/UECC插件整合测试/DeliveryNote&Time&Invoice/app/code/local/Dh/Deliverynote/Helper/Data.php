<?php

/**
 * Deliverynote helper
 *
 * @category	Dh
 * @package		Dh_Deliverynote
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Dh_Deliverynote_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Return the front end label as defined in config
	 *
	 * @return string
	*/
	public function getFrontendLabel()
	{
		return Mage::getStoreConfig('deliverynote_options/basic_settings/frontend_label');
	}
}