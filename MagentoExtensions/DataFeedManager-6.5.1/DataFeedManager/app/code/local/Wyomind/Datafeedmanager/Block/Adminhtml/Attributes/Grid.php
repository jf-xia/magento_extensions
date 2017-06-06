<?php

class Wyomind_Datafeedmanager_Block_Adminhtml_Attributes_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {

        parent::__construct();
        $this->setId('datafeedmanagerGrid');
        $this->setDefaultSort('datafeedmanager_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('datafeedmanager/attributes')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('attribute_id', array(
            'header' => Mage::helper('datafeedmanager')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'attribute_id',
            'filter' => false,
        ));
        $this->addColumn('attribute_name', array(
            'header' => Mage::helper('datafeedmanager')->__('Code'),
            'align' => 'left',
            'index' => 'attribute_name',
            'filter' => false,
            'renderer' => 'Wyomind_Datafeedmanager_Block_Adminhtml_Attributes_Renderer_Code',
        ));

       
        $this->addColumn('action', array(
            'header' => Mage::helper('datafeedmanager')->__('Action'),
            'align' => 'right',
            'index' => 'action',
            'filter' => false,
            'width' => '150px',
            'sortable' => false,
            'renderer' => 'Wyomind_Datafeedmanager_Block_Adminhtml_Attributes_Renderer_Action',
        ));




        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}