<?php

class Topbuy_Searchseo_Block_Adminhtml_Searchseo_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("searchseoGrid");
        $this->setDefaultSort("rowid");
        $this->setDefaultDir("ASC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel("searchseo/searchseo")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn("rowid", array(
            "header" => Mage::helper("searchseo")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "rowid",
        ));

        $this->addColumn("searchtitle", array(
            "header" => Mage::helper("searchseo")->__("searchtitle"),
            "index" => "searchtitle",
        ));
        $this->addColumn("categoryid", array(
            "header" => Mage::helper("searchseo")->__("categoryid"),
            "index" => "categoryid",
        ));
        $this->addColumn("relid", array(
            "header" => Mage::helper("searchseo")->__("relid"),
            "index" => "relid",
        ));
        $this->addColumn("metatitle", array(
            "header" => Mage::helper("searchseo")->__("metatitle"),
            "index" => "metatitle",
        ));
        $this->addColumn("metakeywords", array(
            "header" => Mage::helper("searchseo")->__("metakeywords"),
            "index" => "metakeywords",
        ));
        $this->addColumn("metadescription", array(
            "header" => Mage::helper("searchseo")->__("metadescription"),
            "index" => "metadescription",
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('rowid');
        $this->getMassactionBlock()->setFormFieldName('rowids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_searchseo', array(
            'label' => Mage::helper('searchseo')->__('Remove Searchseo'),
            'url' => $this->getUrl('*/adminhtml_searchseo/massRemove'),
            'confirm' => Mage::helper('searchseo')->__('Are you sure?')
        ));
        return $this;
    }

}