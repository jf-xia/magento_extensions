<?php class ThemeOptions_ExtraConfig_Model_Positionx
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('ExtraConfig')->__('Select')),
            array('value'=>'left', 'label'=>Mage::helper('ExtraConfig')->__('left')),
            array('value'=>'center', 'label'=>Mage::helper('ExtraConfig')->__('center')), 
            array('value'=>'right', 'label'=>Mage::helper('ExtraConfig')->__('right'))     
        );
    }

}?>