<?php

class Mdlext_Mdloption_Model_Config_Styles 
{
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label'=>Mage::helper('adminhtml')->__('no-repeat')),
            array('value' => '2', 'label'=>Mage::helper('adminhtml')->__('repeat')),
			array('value' => '3', 'label'=>Mage::helper('adminhtml')->__('repeat-x')),
			array('value' => '4', 'label'=>Mage::helper('adminhtml')->__('repeat-y')),
        );
    }
}
?>