<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * Create a link in system config to the 4-Tell site
 * 
 */
class FourTell_Recommend_Block_System_Config_Form_Field_Upload extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
   		return "<a href='" . Mage::helper("adminhtml")->getUrl("recommend/adminhtml_uploadform/index/") ."'>Upload Settings and Upload Data</a>";
    }
}