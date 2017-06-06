<?php

class Topbuy_Searchseo_Block_Adminhtml_Searchseo_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {

        parent::__construct();
        $this->_objectId = "rowid";
        $this->_blockGroup = "searchseo";
        $this->_controller = "adminhtml_searchseo";
        $this->_updateButton("save", "label", Mage::helper("searchseo")->__("Save Item"));
        $this->_updateButton("delete", "label", Mage::helper("searchseo")->__("Delete Item"));

        $this->_addButton("saveandcontinue", array(
            "label" => Mage::helper("searchseo")->__("Save And Continue Edit"),
            "onclick" => "saveAndContinueEdit()",
            "class" => "save",
                ), -100);



        $this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
    }

    public function getHeaderText() {
        if (Mage::registry("searchseo_data") && Mage::registry("searchseo_data")->getId()) {

            return Mage::helper("searchseo")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("searchseo_data")->getId()));
        } else {

            return Mage::helper("searchseo")->__("Add Item");
        }
    }

}