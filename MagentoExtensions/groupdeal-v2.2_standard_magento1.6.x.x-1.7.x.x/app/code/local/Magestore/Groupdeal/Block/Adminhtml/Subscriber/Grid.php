<?php

class Magestore_Groupdeal_Block_Adminhtml_Subscriber_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct(){
		parent::__construct();
		$this->setId('subscriberGrid');
		$this->setDefaultSort('subscriber_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection(){
		$collection = Mage::getModel('groupdeal/subscriber')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns(){
		$this->addColumn('subscriber_id', array(
			'header'    => Mage::helper('groupdeal')->__('ID'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'subscriber_id',
		));
		
		$this->addColumn('email', array(
			'header'    => Mage::helper('groupdeal')->__('Email'),
			'align'     =>'left',
			'index'     => 'email',
		));
		
		$this->addColumn('price_from', array(
			'header'    => Mage::helper('groupdeal')->__('Price From'),
			'align'     =>'left',
			'index'     => 'price_from',
			'type'  	=> 'price',
			'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),			  
		));
		
		$this->addColumn('price_to', array(
			'header'    => Mage::helper('groupdeal')->__('Price To'),
			'align'     =>'left',
			'index'     => 'price_to',
			'type'  	=> 'price',
			'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),			  
		));

		$this->addColumn('status', array(
			'header'    => Mage::helper('groupdeal')->__('Status'),
			'align'     => 'left',
			'width'     => '80px',
			'index'     => 'status',
			'type'      => 'options',
			'options'   => array(
				1 => 'Enabled',
				0 => 'Disabled',
			),
		));
		
		$this->addColumn('action',
			array(
				'header'    =>  Mage::helper('groupdeal')->__('Action'),
				'width'     => '50px',
				'type'      => 'action',
				'getter'    => 'getId',
				'actions'   => array(
					array(
						'caption'   => Mage::helper('groupdeal')->__('Edit'),
						'url'       => array('base'=> '*/*/edit'),
						'field'     => 'id'
					)
					),
				'filter'    => false,
				'sortable'  => false,
				'index'     => 'stores',
				'is_system' => true,
		));
		
		return parent::_prepareColumns();
	}

	protected function _prepareMassaction(){
		$this->setMassactionIdField('deal_id');
		$this->getMassactionBlock()->setFormFieldName('deal');
		
		$this->getMassactionBlock()->addItem('delete', array(
			'label'    => Mage::helper('groupdeal')->__('Delete'),
			'url'      => $this->getUrl('*/*/massDelete'),
			'confirm'  => Mage::helper('groupdeal')->__('Are you sure?')
		));

		$statuses = Mage::getSingleton('groupdeal/status')->getOptionArray();
		
		array_push($statuses, array('label'=>'', 'value'=>''));
		$this->getMassactionBlock()->addItem('deal_status', array(
			'label'=> Mage::helper('groupdeal')->__('Change status'),
			'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
			'additional' => array(
				'visibility' => array(
					 'name' => 'deal_status',
					 'type' => 'select',
					 'class' => 'required-entry',
					 'label' => Mage::helper('groupdeal')->__('Status'),
					 'values' => $statuses
				 )
			)
		));
		return $this;
	}

	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}