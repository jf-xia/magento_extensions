<?php

class Topbuy_Searchseo_Block_Adminhtml_Searchseo_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("searchseo_form", array("legend" => Mage::helper("searchseo")->__("Item information")));


        $fieldset->addField("searchtitle", "text", array(
            "label" => Mage::helper("searchseo")->__("searchtitle"),
            "class" => "required-entry",
            "required" => true,
            "name" => "searchtitle",
        ));

        $fieldset->addField("categoryid", "text", array(
            "label" => Mage::helper("searchseo")->__("categoryid"),
            "class" => "required-entry",
            "required" => true,
            "name" => "categoryid",
        ));

        $fieldset->addField("metadescription", "textarea", array(
            "label" => Mage::helper("searchseo")->__("metadescription"),
            "class" => "required-entry",
            "required" => false,
            "name" => "metadescription",
        ));

        $fieldset->addField("metakeywords", "textarea", array(
            "label" => Mage::helper("searchseo")->__("metakeywords"),
            "class" => "required-entry",
            "required" => false,
            "name" => "metakeywords",
        ));

        $fieldset->addField("relid", "text", array(
            "label" => Mage::helper("searchseo")->__("relid"),
            "class" => "required-entry",
            "required" => false,
            "name" => "relid",
        ));

        $fieldset->addField("metatitle", "text", array(
            "label" => Mage::helper("searchseo")->__("metatitle"),
            "class" => "required-entry",
            "required" => false,
            "name" => "metatitle",
        ));


        if (Mage::getSingleton("adminhtml/session")->getSearchseoData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getSearchseoData());
            Mage::getSingleton("adminhtml/session")->setSearchseoData(null);
        } elseif (Mage::registry("searchseo_data")) {
            $form->setValues(Mage::registry("searchseo_data")->getData());
        }
        return parent::_prepareForm();
    }

}
