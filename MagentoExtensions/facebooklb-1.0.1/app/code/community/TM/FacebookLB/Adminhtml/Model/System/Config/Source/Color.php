<?php
class TM_FacebookLB_Adminhtml_Model_System_Config_Source_Color
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'light', 'label'=>Mage::helper('facebooklb')->__('Light')),
            array('value'=>'dark', 'label'=>Mage::helper('facebooklb')->__('Dark'))
        );
    }
}
