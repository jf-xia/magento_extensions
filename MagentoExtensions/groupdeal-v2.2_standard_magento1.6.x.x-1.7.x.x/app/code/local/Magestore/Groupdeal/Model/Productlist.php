<?php

class Magestore_Groupdeal_Model_Productlist extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('groupdeal/productlist');
    }
	
	public function loadProductlist($dealId, $productId) {
		$collection = $this->getCollection()
			->addFieldToFilter('deal_id', $dealId)
			->addFieldToFilter('product_id', $productId)
			;
			
		$item = $collection->getFirstItem();
		$this->setData($item->getData());
		return $this;
	}
}