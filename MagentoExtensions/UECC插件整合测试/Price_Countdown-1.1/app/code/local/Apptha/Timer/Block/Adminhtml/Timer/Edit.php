<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file PRICE COUNTDOWN-LICENSE.txt.
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento 1.4.x and 1.5.x COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento 1.4.x and 1.5.x COMMUNITY edition.
 * =================================================================
 */

class Apptha_Timer_Block_Adminhtml_Timer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'timer';
        $this->_controller = 'adminhtml_timer';
        
        $this->_updateButton('save', 'label', Mage::helper('timer')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('timer')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('timer_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'timer_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'timer_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('timer_data') && Mage::registry('timer_data')->getId() ) {
            return Mage::helper('timer')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('timer_data')->getTitle()));
        } else {
            return Mage::helper('timer')->__('Add Item');
        }
    }
}