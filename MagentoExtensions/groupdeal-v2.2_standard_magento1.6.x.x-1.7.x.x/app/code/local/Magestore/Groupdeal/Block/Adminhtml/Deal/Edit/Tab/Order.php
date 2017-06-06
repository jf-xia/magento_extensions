<?php

class Magestore_Groupdeal_Block_Adminhtml_Deal_Edit_Tab_Order
	 extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
  	{
        parent::__construct();
		$this->setId('orderlist_grid');
      	$this->setDefaultSort('entity_id');
      	$this->setDefaultDir('DESC');
      	$this->setSaveParametersInSession(true);
  	}

    //return category collection filtered by store
	protected function _prepareCollection(){
		$dealId = $this->getRequest()->getParam('id');
		$collection = Mage::getResourceModel('groupdeal/orderlist_collection')->getOrders($dealId);
		/* $orderIds = Mage::helper('groupdeal')->getGroupdealOrderIds($dealId);
		
       	$collection = Mage::getResourceModel('sales/order_grid_collection')
	   		->addFieldToFilter('entity_id', array('in' => $orderIds)); */
		
		$this->setCollection($collection);
		return 	parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
		
		$this->addColumn('real_order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
        ));
		
		$this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Customer Name'),
            'index' => 'billing_name',
			'width' => '200px',
        ));
		
		$this->addColumn('quantity', array(
            'header' => Mage::helper('sales')->__('Quantity'),
            'index' => 'quantity',
			'width' => '40px',
        ));
		
        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '150px',
        ));
		

        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('groupdeal/status')->getOrderStatus(),
        ));
		
		
		return parent::_prepareColumns();
		
    }
	
	public function getRowUrl($row){
      return $this->getUrl('adminhtml/sales_order/view', array('order_id' => $row->getEntityId()));
  	}
}