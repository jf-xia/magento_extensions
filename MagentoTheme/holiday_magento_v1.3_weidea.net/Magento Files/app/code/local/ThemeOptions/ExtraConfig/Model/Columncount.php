<?php class ThemeOptions_ExtraConfig_Model_Columncount
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'2', 'label'=>Mage::helper('ExtraConfig')->__('2')),
            array('value'=>'3', 'label'=>Mage::helper('ExtraConfig')->__('3')),
            array('value'=>'4', 'label'=>Mage::helper('ExtraConfig')->__('4')) 
        );
    }

}?>