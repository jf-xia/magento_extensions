<?php

class Brainsins_Recsins_Block_Adminhtml_Recsins_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'recsins';
        $this->_controller = 'adminhtml_recsins';
        
        $this->_updateButton('save', 'label', Mage::helper('recsins')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('recsins')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('recsins_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'recsins_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'recsins_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('recsins_data') && Mage::registry('recsins_data')->getId() ) {
            return Mage::helper('recsins')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('recsins_data')->getTitle()));
        } else {
            return Mage::helper('recsins')->__('Add Item');
        }
    }
}