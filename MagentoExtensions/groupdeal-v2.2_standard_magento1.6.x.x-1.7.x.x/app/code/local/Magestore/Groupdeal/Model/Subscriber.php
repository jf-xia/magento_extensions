<?php

class Magestore_Groupdeal_Model_Subscriber extends Mage_Core_Model_Abstract{
    public function _construct()
    {
        parent::_construct();
        $this->_init('groupdeal/subscriber');
    }
	
	
	public function getCategoryIds(){
		return explode(',', $this->getCategories());
	}
}