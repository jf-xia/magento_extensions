<?php class ThemeOptions_ExtraConfig_Model_Positiony
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('ExtraConfig')->__('Select')),
            array('value'=>'top', 'label'=>Mage::helper('ExtraConfig')->__('top')),
            array('value'=>'center', 'label'=>Mage::helper('ExtraConfig')->__('center')), 
            array('value'=>'bottom', 'label'=>Mage::helper('ExtraConfig')->__('bottom'))     
        );
    }

}?>