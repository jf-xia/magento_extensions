<?php class ThemeOptions_ExtraConfig_Model_Repeat
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('ExtraConfig')->__('Select')),
            array('value'=>'no-repeat', 'label'=>Mage::helper('ExtraConfig')->__('no-repeat')),
            array('value'=>'repeat', 'label'=>Mage::helper('ExtraConfig')->__('repeat')),
            array('value'=>'repeat-x', 'label'=>Mage::helper('ExtraConfig')->__('repeat-x')),   
            array('value'=>'repeat-y', 'label'=>Mage::helper('ExtraConfig')->__('repeat-y'))        
        );
    }

}?>