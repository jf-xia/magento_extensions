<?php
class CommerceStack_Recommender_Block_System_Config_Form_Apikeyurl extends Mage_Adminhtml_Block_System_Config_Form_Field
{  
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    { 
        $url = $this->getUrl('recommender/account/getapikey');
        $element->setValue($url);

        return parent::_getElementHtml($element);
    }
    
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = $this->_getElementHtml($element);
        return $html;
    }
}