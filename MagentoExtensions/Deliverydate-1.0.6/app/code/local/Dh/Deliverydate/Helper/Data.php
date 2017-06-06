<?php

/**
 * Deliverydate helper
 *
 * @category	Dh
 * @package		Dh_Deliverydate
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Dh_Deliverydate_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Return the front end label as defined in config
	 *
	 * @return string
	*/
	public function getFrontendLabel()
	{
		return Mage::getStoreConfig('deliverydate_options/basic_settings/frontend_label');
	}
}