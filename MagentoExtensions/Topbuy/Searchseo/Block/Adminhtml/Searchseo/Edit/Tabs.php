<?php

class Topbuy_Searchseo_Block_Adminhtml_Searchseo_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId("searchseo_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("searchseo")->__("Item Information"));
    }

    protected function _beforeToHtml() {
        $this->addTab("form_section", array(
            "label" => Mage::helper("searchseo")->__("Item Information"),
            "title" => Mage::helper("searchseo")->__("Item Information"),
            "content" => $this->getLayout()->createBlock("searchseo/adminhtml_searchseo_edit_tab_form")->toHtml(),
        ));
        return parent::_beforeToHtml();
    }

}
