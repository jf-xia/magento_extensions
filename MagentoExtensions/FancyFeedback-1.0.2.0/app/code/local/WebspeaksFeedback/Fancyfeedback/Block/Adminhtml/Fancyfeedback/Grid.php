<?php

class WebspeaksFeedback_Fancyfeedback_Block_Adminhtml_Fancyfeedback_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('fancyfeedbackGrid');
      $this->setDefaultSort('fancyfeedback_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('fancyfeedback/fancyfeedback')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('fancyfeedback_id', array(
          'header'    => Mage::helper('fancyfeedback')->__('ID'),
          'align'     =>'right',
          'index'     => 'fancyfeedback_id',
          'width'     => '80px',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('fancyfeedback')->__('Name'),
          'align'     =>'left',
          'index'     => 'name',
      ));

      $this->addColumn('email', array(
          'header'    => Mage::helper('fancyfeedback')->__('Email'),
          'align'     =>'left',
          'index'     => 'email',
      ));

      $this->addColumn('comment', array(
          'header'    => Mage::helper('fancyfeedback')->__('Comment'),
          'align'     =>'left',
          'width'     => '380px',
          'index'     => 'comment',
      ));

      $this->addColumn('date', array(
          'header'    => Mage::helper('fancyfeedback')->__('Received On'),
          'align'     =>'left',
          'index'     => 'created_time',
      ));

      $this->addColumn('reply', array(
          'header'    => Mage::helper('fancyfeedback')->__('Reply'),
          'align'     =>'left',
          'index'     => 'reply',
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
                'width'     => '80',
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
        $this->setMassactionIdField('fancyfeedback_id');
        $this->getMassactionBlock()->setFormFieldName('fancyfeedback');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('fancyfeedback')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('fancyfeedback')->__('Are you sure?')
        ));

      /*  $statuses = Mage::getSingleton('fancyfeedback/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('fancyfeedback')->__('Change status'),
             'url'  => $this->getUrl('*//*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('fancyfeedback')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));*/
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}