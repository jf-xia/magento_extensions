<?php class ThemeOptions_ExtraConfig_Model_Loader
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'clickable', 'label'=>Mage::helper('ExtraConfig')->__('OnClick Load More Button')),
            array('value'=>'automatic', 'label'=>Mage::helper('ExtraConfig')->__('Auto on Scroll'))
            
        );
    }

}?>