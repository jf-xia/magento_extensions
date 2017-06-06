<?php
class Wee_Fpc_Block_Adminhtml_System_Config_Form_Fieldset extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        if (Mage::getModel('wee_fpc/fullpagecache')->hasValidLicense()) {
            return parent::render($element);
        }
    
        $html = $this->_getHeaderHtml($element);
        $html .= Mage::helper('core')->__('The current license is no longer valid. Please contact <a href="mailto:support@mgt-commerce.com">support@mgt-commerce.com</a>');
        $html .= $this->_getFooterHtml($element);
        return $html;
    }
}
	?>