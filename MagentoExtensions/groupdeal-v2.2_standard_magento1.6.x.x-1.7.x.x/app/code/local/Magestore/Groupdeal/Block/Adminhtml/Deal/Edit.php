<?php

class Magestore_Groupdeal_Block_Adminhtml_Deal_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'groupdeal';
        $this->_controller = 'adminhtml_deal';
        
        $this->_updateButton('save', 'label', Mage::helper('groupdeal')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('groupdeal')->__('Delete'));
		$this->_updateButton('duplicate', 'label', Mage::helper('groupdeal')->__('Duplicate'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('deal_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'deal_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'deal_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('deal_data') && Mage::registry('deal_data')->getId() ) {
            return Mage::helper('groupdeal')->__("Edit Deal '%s'", $this->htmlEscape(Mage::registry('deal_data')->getDealTitle()));
        } else {
            return Mage::helper('groupdeal')->__('Add Deal');
        }
    }
}