<?php
class TM_FacebookLB_Adminhtml_Model_System_Config_Source_Verb
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'like', 'label'=>Mage::helper('facebooklb')->__('Like')),
            array('value'=>'recommend', 'label'=>Mage::helper('facebooklb')->__('Recommend'))
        );
    }
}