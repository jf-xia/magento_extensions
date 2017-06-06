<?php

class Magebuzz_Catsidebarnav_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function isEnabled() {
		return (int)Mage::getStoreConfig('catsidebarnav/display_settings/enabled');
	}
	public function displayOnSideBar() {
		return Mage::getStoreConfig('catsidebarnav/display_settings/position');
	}
	public function getShowType(){
		return Mage::getStoreConfig('catsidebarnav/display_settings/show_type');
	}
}