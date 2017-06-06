<?php

class EM_Megamenupro_Block_Adminhtml_Menueditor_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('megamenuproGrid');
      $this->setDefaultSort('megamenupro_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('megamenupro/megamenupro')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('megamenupro_id', array(
          'header'    => Mage::helper('megamenupro')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'megamenupro_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('megamenupro')->__('Name'),
          'align'     =>'left',
          'index'     => 'name',
      ));
	  
	  $this->addColumn('type', array(
          'header'    => Mage::helper('megamenupro')->__('Type'),
          'align'     =>'left',
          'index'     => 'type',
		  'type'      => 'options',
          'options'   => array(
              1 => Mage::helper('megamenupro')->__('Vertical'),
              0 => Mage::helper('megamenupro')->__('Horizantal'),
          ),
      ));

	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('megamenupro')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

      $this->addColumn('status', array(
          'header'    => Mage::helper('megamenupro')->__('Status'),
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
                'header'    =>  Mage::helper('megamenupro')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('megamenupro')->__('Menu Editor'),
                        'url'       => array('base'=> '*/adminhtml_menueditor/change'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		//$this->addExportType('*/*/exportCsv', Mage::helper('megamenupro')->__('CSV'));
		//$this->addExportType('*/*/exportXml', Mage::helper('megamenupro')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('megamenupro_id');
        $this->getMassactionBlock()->setFormFieldName('megamenupro');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('megamenupro')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('megamenupro')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('megamenupro/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('megamenupro')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('megamenupro')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}