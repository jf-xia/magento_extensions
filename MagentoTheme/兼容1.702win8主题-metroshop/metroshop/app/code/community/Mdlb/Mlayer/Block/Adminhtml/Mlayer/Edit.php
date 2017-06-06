<?php

class Mdlb_Mlayer_Block_Adminhtml_Mlayer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'mlayer';
        $this->_controller = 'adminhtml_mlayer';
        
        $this->_updateButton('save', 'label', Mage::helper('mlayer')->__('Save Banner'));
        $this->_updateButton('delete', 'label', Mage::helper('mlayer')->__('Delete Banner'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('mlayer_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'mlayer_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'mlayer_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('mlayer_data') && Mage::registry('mlayer_data')->getId() ) {
            return Mage::helper('mlayer')->__("Edit Banner '%s'", $this->htmlEscape(Mage::registry('mlayer_data')->getTitle()));
        } else {
            return Mage::helper('mlayer')->__('Add Banner');
        }
    }
}