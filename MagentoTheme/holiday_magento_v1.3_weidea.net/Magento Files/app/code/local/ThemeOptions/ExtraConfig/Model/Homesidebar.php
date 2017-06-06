<?php class ThemeOptions_ExtraConfig_Model_Homesidebar
{
    public function toOptionArray()
    {
        return array(
            
            array('value'=>'left', 'label'=>Mage::helper('ExtraConfig')->__('Left Side')),
            array('value'=>'right', 'label'=>Mage::helper('ExtraConfig')->__('Right Side')),
            array('value'=>'both', 'label'=>Mage::helper('ExtraConfig')->__('Both Side')),
            array('value'=>'not', 'label'=>Mage::helper('ExtraConfig')->__('Not Display'))
            
        );
    }

}?>