<?php

class Wyomind_Datafeedmanager_Block_Adminhtml_Options_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {

        parent::__construct();
        $this->setId('datafeedmanagerGrid');
        $this->setDefaultSort('datafeedmanager_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('datafeedmanager/options')->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('option_id', array(
            'header' => Mage::helper('datafeedmanager')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'option_id',
            'filter' => false,
        ));
        $this->addColumn('option_name', array(
            'header' => Mage::helper('datafeedmanager')->__('Code'),
            'align' => 'left',
            'index' => 'option_name',
            'filter' => false,
            'renderer' => 'Wyomind_Datafeedmanager_Block_Adminhtml_Options_Renderer_Code',
        ));

       
        $this->addColumn('action', array(
            'header' => Mage::helper('datafeedmanager')->__('Action'),
            'align' => 'right',
            'index' => 'action',
            'filter' => false,
            'width' => '150px',
            'sortable' => false,
            'renderer' => 'Wyomind_Datafeedmanager_Block_Adminhtml_Options_Renderer_Action',
        ));




        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}