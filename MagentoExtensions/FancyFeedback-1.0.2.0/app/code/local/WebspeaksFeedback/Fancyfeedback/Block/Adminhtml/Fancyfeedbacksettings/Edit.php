<?php

class WebspeaksFeedback_Fancyfeedback_Block_Adminhtml_Fancyfeedbacksettings_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'fancyfeedback';
        $this->_controller = 'adminhtml_fancyfeedbacksettings';
        
        $this->_updateButton('delete', 'label', Mage::helper('fancyfeedbacksettings')->__('Delete Item'));
        $this->_updateButton('save', 'label', Mage::helper('fancyfeedbacksettings')->__('Reply'));
		
       /* $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);*/

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('fancyfeedbacksettings_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'fancyfeedbacksettings_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'fancyfeedbacksettings_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('fancyfeedbacksettings_data') && Mage::registry('fancyfeedbacksettings_data')->getId() ) {
            return Mage::helper('fancyfeedbacksettings')->__("Reply to '%s'", $this->htmlEscape(Mage::registry('fancyfeedbacksettings_data')->getName()));
        } else {
            return Mage::helper('fancyfeedbacksettings')->__('Add Item');
        }
    }
}