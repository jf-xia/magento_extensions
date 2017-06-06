<?php

class Magestore_Groupdeal_Block_Adminhtml_Deal_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct(){
		parent::__construct();
		$this->setId('dealGrid');
		$this->setDefaultSort('deal_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection(){
		$collection = Mage::getModel('groupdeal/deal')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns(){
		$this->addColumn('deal_id', array(
			'header'    => Mage::helper('groupdeal')->__('ID'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'deal_id',
		));

		$this->addColumn('deal_title', array(
			'header'    => Mage::helper('groupdeal')->__('Title'),
			'align'     =>'left',
			'index'     => 'deal_title',
		));
		
		$this->addColumn('featured', array(
			'header'    => Mage::helper('groupdeal')->__('Featured'),
			'align'     =>'left',
			'index'     => 'featured',
			'type'      => 'options',
			'options'   => array(
				1 => $this->__('Yes'),
				0 => $this->__('No'),
			),			
		));		
		
		$this->addColumn('deal_price', array(
			'header'    => Mage::helper('groupdeal')->__('Price'),
			'align'     =>'left',
			'index'     => 'deal_price',
			'type'  	=> 'price',
			'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),			  
		));
		
		$this->addColumn('deal_value', array(
			'header'    => Mage::helper('groupdeal')->__('Value'),
			'align'     =>'left',
			'index'     => 'deal_value',
			'type'  	=> 'price',
			'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),			  
		));
		
		$this->addColumn('bought', array(
			'header'    => Mage::helper('groupdeal')->__('Bought'),
			'align'     =>'left',
			'index'     => 'bought',
			'width'		=> '50px',	  
		));
		
		$this->addColumn('minimum_purchase', array(
			'header'    => Mage::helper('groupdeal')->__('Target'),
			'align'     =>'left',
			'index'     => 'minimum_purchase',
			'width'		=> '50px',	  
		));
		
		$this->addColumn('start_datetime', array(
			'header'    => Mage::helper('groupdeal')->__('Start Time'),
			'align'     =>'left',
			'index'     => 'start_datetime',
			'type'		=> 'datetime',
			'width'		=> '160px',	
		));
		
		$this->addColumn('end_datetime', array(
			'header'    => Mage::helper('groupdeal')->__('End Time'),
			'align'     =>'left',
			'index'     => 'end_datetime',
			'type'		=> 'datetime',
			'width'		=> '160px',
		));
	
		$this->addColumn('deal_status', array(
			'header'    => Mage::helper('groupdeal')->__('Status'),
			'align'     => 'left',
			'width'     => '80px',
			'index'     => 'deal_status',
			'type'      => 'options',
			'options'   => array(
				6 => 'Waiting',
				5 => 'Opening',
				4 => 'Reached',
				3 => 'Unreached',
				2 => 'End',
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

		$this->addExportType('*/*/exportCsv', Mage::helper('groupdeal')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('groupdeal')->__('XML'));

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