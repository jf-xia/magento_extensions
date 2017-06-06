<?php
class Magestore_Groupdeal_Block_Mydeal extends Mage_Core_Block_Template{	
	
	public function __construct(){
   		parent::__construct();
		$this->setOrders($this->getMyOrders());
   	}
	
	public function _prepareLayout(){
		$headBlock = $this->getLayout()->getBlock('head');
		$headBlock->setTitle(Mage::helper('groupdeal')->__('My Deal'));
		return parent::_prepareLayout();
    }
	
	public function getMyOrders(){
		$id = $this->getRequest()->getParam('id');
		$customerId = Mage::getSingleton('customer/session')->getCustomerId();
		$collection = Mage::getModel('sales/order')->getCollection()
				->addFieldToFilter('customer_id', $customerId);
		
		$realOrderIds = array();
		foreach($collection as $item){
			$realOrderIds[] = $item->getId();
		}
		
		
		$orders = Mage::getModel('groupdeal/orderlist')->getCollection()			
					->addFieldToFilter('order_id', array('in' => $realOrderIds));
		
		
		if($id)
			$orders = $orders->addFieldToFilter('orderlist_id', $id);
		
		return $orders;
	}
	
	public function getDealViewUrl($order){
        return $this->getUrl('*/index/deal', array('id' => $order->getDealId()));
    }
	
	public function getOrderViewUrl($order){
		return $this->getUrl('sales/order/view', array('order_id' => $order->getOrderId()));
	}
	
	public function getDeal($order){
		return Mage::getModel('groupdeal/deal')->load($order->getDealId());
	}
	
	public function getRealOrder($order){// order in magento system
		return Mage::getModel('sales/order')->load($order->getOrderId());
	}
	
	public function getStatusList(){
		return array(
			'5' => Mage::helper('groupdeal')->__('Waiting'), //deal is opening
			'4' => Mage::helper('groupdeal')->__('On'), //deal is reached
			'3' => Mage::helper('groupdeal')->__('Cancel'), //deal is unreached
		);
	}
}