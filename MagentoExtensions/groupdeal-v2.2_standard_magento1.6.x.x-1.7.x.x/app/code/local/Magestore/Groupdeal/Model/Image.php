<?php

class Magestore_Groupdeal_Model_Image extends Mage_Core_Model_Abstract{
    public function _construct()
    {
        parent::_construct();
        $this->_init('groupdeal/image');
    }
	
	public function getFullUrl(){
		return Mage::getBaseUrl('media') . $this->getImageUrl();
	}
}