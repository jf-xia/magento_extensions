<?php

class Topbuy_Searchseo_Block_Adminhtml_Searchseo extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {

        $this->_controller = "adminhtml_searchseo";
        $this->_blockGroup = "searchseo";
        $this->_headerText = Mage::helper("searchseo")->__("Searchseo Manager");
        $this->_addButtonLabel = Mage::helper("searchseo")->__("Add New Item");
        parent::__construct();
    }

}