<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * Display the current version of the 4-Tell service
 * 
 */
class FourTell_Recommend_Block_System_Config_Form_Field_Version extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
   		return "2";
    }
}