<?php

class Magestore_Groupdeal_Block_Adminhtml_Subscriber_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'groupdeal';
        $this->_controller = 'adminhtml_subscriber';
        
        $this->_updateButton('save', 'label', Mage::helper('groupdeal')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('groupdeal')->__('Delete'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('subscriber_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'subscriber_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'subscriber_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('subscriber_data') && Mage::registry('subscriber_data')->getId() ) {
            return Mage::helper('groupdeal')->__("Edit Subscriber '%s'", $this->htmlEscape(Mage::registry('subscriber_data')->getEmail()));
        } else {
            return Mage::helper('groupdeal')->__('Add Subscriber');
        }
    }
}