<?php

class WebspeaksFeedback_Fancyfeedback_Block_Adminhtml_Fancyfeedback_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'fancyfeedback';
        $this->_controller = 'adminhtml_fancyfeedback';
        
        $this->_updateButton('delete', 'label', Mage::helper('fancyfeedback')->__('Delete Item'));
        $this->_updateButton('save', 'label', Mage::helper('fancyfeedback')->__('Reply'));
		
       /* $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);*/

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('fancyfeedback_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'fancyfeedback_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'fancyfeedback_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('fancyfeedback_data') && Mage::registry('fancyfeedback_data')->getId() ) {
            return Mage::helper('fancyfeedback')->__("Reply to '%s'", $this->htmlEscape(Mage::registry('fancyfeedback_data')->getName()));
        } else {
            return Mage::helper('fancyfeedback')->__('Add Item');
        }
    }
}