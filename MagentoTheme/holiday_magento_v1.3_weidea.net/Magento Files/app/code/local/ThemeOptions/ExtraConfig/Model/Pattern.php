<?php class ThemeOptions_ExtraConfig_Model_Pattern
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('ExtraConfig')->__('---Select---')),
            array('value'=>'pattern1.png', 'label'=>Mage::helper('ExtraConfig')->__('pattern1')),
            array('value'=>'pattern2.png', 'label'=>Mage::helper('ExtraConfig')->__('pattern2')),   
            array('value'=>'pattern3.png', 'label'=>Mage::helper('ExtraConfig')->__('pattern3')),
            array('value'=>'pattern4.png', 'label'=>Mage::helper('ExtraConfig')->__('pattern4')),
            array('value'=>'pattern5.png', 'label'=>Mage::helper('ExtraConfig')->__('pattern5')),
            array('value'=>'pattern6.png', 'label'=>Mage::helper('ExtraConfig')->__('pattern6')),
            array('value'=>'pattern7.png', 'label'=>Mage::helper('ExtraConfig')->__('pattern7')),
            array('value'=>'pattern8.png', 'label'=>Mage::helper('ExtraConfig')->__('pattern8')),
            array('value'=>'pattern9.png', 'label'=>Mage::helper('ExtraConfig')->__('pattern9')),
            array('value'=>'pattern10.png', 'label'=>Mage::helper('ExtraConfig')->__('pattern10'))
        );
    }

}?>