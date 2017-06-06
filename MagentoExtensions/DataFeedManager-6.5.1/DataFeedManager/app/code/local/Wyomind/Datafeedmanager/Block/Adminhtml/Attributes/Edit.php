<?php

class Wyomind_Datafeedmanager_Block_Adminhtml_Attributes_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {

        parent::__construct();

        $this->_objectId = 'attribute_id';
        $this->_controller = 'adminhtml_attributes';
        $this->_blockGroup = 'datafeedmanager';


        if (Mage::registry('datafeedmanager_data')->getAttributeId()) {
           
            $this->_addButton('continue', array(
                'label' => Mage::helper('adminhtml')->__('Save & Continue'),
                'onclick' => "$('continue').value=1; editForm.submit();",
                'class' => 'add',
            ));
            
        }
    }

    public function getHeaderText() {
        if (Mage::registry('datafeedmanager_data') && Mage::registry('datafeedmanager_data')->getAttributeId()) {
            return Mage::helper('datafeedmanager')->__("Edit custom attribute  '%s'", $this->htmlEscape(Mage::registry('datafeedmanager_data')->getAttribute_name()));
        } else {
            return Mage::helper('datafeedmanager')->__('New custom attribute');
        }
       
    }

}