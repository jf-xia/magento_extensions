<?php

class Thylak_Artist_Block_Adminhtml_Artist_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'artist';
        $this->_controller = 'adminhtml_artist';
        
        $this->_updateButton('save', 'label', Mage::helper('artist')->__('Save Artist'));
        $this->_updateButton('delete', 'label', Mage::helper('artist')->__('Delete Artist'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('artist_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'artist_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'artist_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('artist_data') && Mage::registry('artist_data')->getId() ) {
            return Mage::helper('artist')->__("Edit Artist '%s'", $this->htmlEscape(Mage::registry('artist_data')->getFirstname()));
        } else {
            return Mage::helper('artist')->__('Add Artist');
        }
    }
}