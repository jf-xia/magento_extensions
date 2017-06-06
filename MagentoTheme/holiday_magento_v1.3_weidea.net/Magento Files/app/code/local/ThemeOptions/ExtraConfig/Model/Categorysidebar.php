<?php class ThemeOptions_ExtraConfig_Model_Categorysidebar
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'left', 'label'=>Mage::helper('ExtraConfig')->__('Left Side')),
            array('value'=>'right', 'label'=>Mage::helper('ExtraConfig')->__('Right Side')),
            array('value'=>'full', 'label'=>Mage::helper('ExtraConfig')->__('Full Width'))
            
        );
    }

}?>