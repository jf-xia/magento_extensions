<?php

class WebspeaksFeedback_Fancyfeedback_Block_Adminhtml_Fancyfeedbacksettings_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('fancyfeedbacksettingsGrid');
      $this->setDefaultSort('fancyfeedbacksettings_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('fancyfeedback/fancyfeedbacksettings')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('fancyfeedbacksettings_id', array(
          'header'    => Mage::helper('fancyfeedback')->__('ID'),
          'align'     =>'right',
          'index'     => 'fancyfeedbacksettings_id',
          'width'     => '80px',
      ));

      /*$this->addColumn('status', array(
          'header'    => Mage::helper('fancyfeedback')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));*/
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('fancyfeedback')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('fancyfeedback')->__('Reply'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('fancyfeedback')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('fancyfeedback')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('fancyfeedbacksettings_id');
        $this->getMassactionBlock()->setFormFieldName('fancyfeedbacksettings');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('fancyfeedback')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('fancyfeedback')->__('Are you sure?')
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}