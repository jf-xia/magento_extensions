<?php

class Mdlext_Mdloption_Model_Config_Homepagecontent 
{
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label'=>Mage::helper('adminhtml')->__('Content with one column')),
            array('value' => '2', 'label'=>Mage::helper('adminhtml')->__('Content with left column')),
			array('value' => '3', 'label'=>Mage::helper('adminhtml')->__('Content with right column')),
        );
    }
}
?>