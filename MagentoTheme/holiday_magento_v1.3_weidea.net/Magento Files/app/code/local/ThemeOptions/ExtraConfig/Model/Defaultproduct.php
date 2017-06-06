<?php class ThemeOptions_ExtraConfig_Model_Defaultproduct
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'10', 'label'=>Mage::helper('ExtraConfig')->__('10')),
            array('value'=>'15', 'label'=>Mage::helper('ExtraConfig')->__('15')),
            array('value'=>'20', 'label'=>Mage::helper('ExtraConfig')->__('20')) 
        );
    }

}?>