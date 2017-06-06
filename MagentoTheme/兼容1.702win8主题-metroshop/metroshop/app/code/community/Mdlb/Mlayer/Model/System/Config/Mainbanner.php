<?php

class Mdlb_Mlayer_Model_System_Config_Mainbanner 
{
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label'=>Mage::helper('adminhtml')->__('Metro Banner')),
            array('value' => '2', 'label'=>Mage::helper('adminhtml')->__('Flex Banner')),
			array('value' => '3', 'label'=>Mage::helper('adminhtml')->__('Sequence Banner')),
            array('value' => '4', 'label'=>Mage::helper('adminhtml')->__('Nivo Banner')),
        );
    }
}