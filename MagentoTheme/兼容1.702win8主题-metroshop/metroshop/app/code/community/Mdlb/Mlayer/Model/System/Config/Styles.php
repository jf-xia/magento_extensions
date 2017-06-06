<?php

class Mdlb_Mlayer_Model_System_Config_Styles 
{
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label'=>Mage::helper('adminhtml')->__('Slide')),
            array('value' => '2', 'label'=>Mage::helper('adminhtml')->__('Fade')),
        );
    }
}