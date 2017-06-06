<?php

class Magestore_Groupdeal_Model_Orderlist extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('groupdeal/orderlist');
    }
	
	public function loadOrderlist($dealId, $orderId) {
		$collection = $this->getCollection()
			->addFieldToFilter('deal_id', $dealId)
			->addFieldToFilter('order_id', $orderId)
			;
			
		$item = $collection->getFirstItem();
		$this->setData($item->getData());
		return $this;
	}
}