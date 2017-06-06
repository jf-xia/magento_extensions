<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * Configuration default for System / Config / Num Sales Files
 * 
 */
class FourTell_Recommend_Block_System_Config_Form_Field_Numsalesfiles extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
   		return "1";
    }
}