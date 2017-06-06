<?php
class CommerceStack_Recommender_Block_System_Config_Form_Updatebutton extends Mage_Adminhtml_Block_System_Config_Form_Field
{  
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    { 
        $this->setElement($element);
        $url = $this->getUrl('recommender/index/requestUpdate');

        $originalData = $element->getOriginalData();
        $label = Mage::helper('recommender')->__($originalData['button_label']);
        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('scalable')
                    ->setLabel($label)
                    //->setOnClick("setLocation('$url')")
                    ->setOnClick("updateCommerceStackRecs('$url')")
                    ->toHtml();

        return $html;
    }
}