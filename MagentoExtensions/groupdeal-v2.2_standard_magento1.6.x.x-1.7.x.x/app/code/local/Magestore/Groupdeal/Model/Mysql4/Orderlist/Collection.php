<?php

class Magestore_Groupdeal_Model_Mysql4_Orderlist_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('groupdeal/orderlist');
    }
	
	public function getOrders($dealId){
		$orderIds = Mage::helper('groupdeal')->getGroupdealOrderIds($dealId);		
		$collection = $this->addFieldToFilter('deal_id', $dealId)
					->getSelect()
					->joinLeft(array('orders' =>$this->getTable('sales/order_grid')),
            		'orders.entity_id = main_table.order_id', array('entity_id' => 'entity_id', 'billing_name' => 'billing_name', 'increment_id' => 'increment_id', 'created_at' => 'created_at', 'status' => 'status'))
					;
		return $this;
	}
}