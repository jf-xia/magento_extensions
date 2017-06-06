<?php class ThemeOptions_ExtraConfig_Model_Productlayout
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'default', 'label'=>Mage::helper('ExtraConfig')->__('default')),
            array('value'=>'horizontal', 'label'=>Mage::helper('ExtraConfig')->__('horizontal')), 
            array('value'=>'vertical', 'label'=>Mage::helper('ExtraConfig')->__('vertical')),
            array('value'=>'custom1', 'label'=>Mage::helper('ExtraConfig')->__('custom1')),
            array('value'=>'custom2', 'label'=>Mage::helper('ExtraConfig')->__('custom2'))
        );
    }

}?>