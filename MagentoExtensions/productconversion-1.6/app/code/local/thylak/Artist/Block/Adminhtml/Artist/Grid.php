<?php

class Thylak_Artist_Block_Adminhtml_Artist_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('artistGrid');
      $this->setDefaultSort('artist_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('artist/artist')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('artist_id', array(
          'header'    => Mage::helper('artist')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'artist_id',
      ));

      $this->addColumn('firstname', array(
          'header'    => Mage::helper('artist')->__('FirstName'),
          'align'     =>'left',
          'index'     => 'firstname',
      ));

	 
      $this->addColumn('lastname', array(
			'header'    => Mage::helper('artist')->__('LastName'),
			'width'     => '150px',
			'index'     => 'lastname',
      ));
	  
	  $this->addColumn('email', array(
			'header'    => Mage::helper('artist')->__('Email'),
			'width'     => '150px',
			'index'     => 'email',
      ));
	  
	  $this->addColumn('password', array(
			'header'    => Mage::helper('artist')->__('Password'),
			'width'     => '150px',
			'index'     => 'password',
      ));
	 
     
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('artist')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('artist')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('artist')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('artist')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('artist_id');
        $this->getMassactionBlock()->setFormFieldName('artist');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('artist')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('artist')->__('Are you sure?')
        ));

        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}