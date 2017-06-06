<?php

class Mdlb_Mlayer_Model_System_Config_Nivoeffects 
{
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label'=>Mage::helper('adminhtml')->__('random')),
            array('value' => '2', 'label'=>Mage::helper('adminhtml')->__('sliceDown')),
			array('value' => '3', 'label'=>Mage::helper('adminhtml')->__('sliceDownLeft')),
            array('value' => '4', 'label'=>Mage::helper('adminhtml')->__('sliceUp')),
			array('value' => '5', 'label'=>Mage::helper('adminhtml')->__('sliceUpLeft')),
            array('value' => '6', 'label'=>Mage::helper('adminhtml')->__('sliceUpDown')),
			array('value' => '7', 'label'=>Mage::helper('adminhtml')->__('sliceUpDownLeft')),
            array('value' => '8', 'label'=>Mage::helper('adminhtml')->__('fold')),
			array('value' => '9', 'label'=>Mage::helper('adminhtml')->__('fade')),
            array('value' => '10', 'label'=>Mage::helper('adminhtml')->__('slideInRight')),
			array('value' => '11', 'label'=>Mage::helper('adminhtml')->__('slideInLeft')),
            array('value' => '12', 'label'=>Mage::helper('adminhtml')->__('boxRandom')),
			array('value' => '13', 'label'=>Mage::helper('adminhtml')->__('boxRain')),
            array('value' => '14', 'label'=>Mage::helper('adminhtml')->__('boxRainReverse')),
			array('value' => '15', 'label'=>Mage::helper('adminhtml')->__('boxRainGrow')),
            array('value' => '16', 'label'=>Mage::helper('adminhtml')->__('boxRainGrowReverse')),
        );
    }
}