<?php
class Mdlext_Mdloption_Block_Adminhtml_System_Config_Form_Field_Styles extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $time = $element->getHtmlId();
        // Get the default HTML for this option
        $html = parent::_getElementHtml($element);
        return $html;
    }
}