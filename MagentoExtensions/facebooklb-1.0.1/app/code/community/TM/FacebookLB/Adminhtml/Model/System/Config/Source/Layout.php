<?php
class TM_FacebookLB_Adminhtml_Model_System_Config_Source_Layout
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'button_count', 'label'=>Mage::helper('facebooklb')->__('button_count')),
            array('value'=>'standard', 'label'=>Mage::helper('facebooklb')->__('standard'))
        );
    }
}
