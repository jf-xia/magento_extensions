<?php class ThemeOptions_ExtraConfig_Model_Ajaxcart
{
    public function toOptionArray()
    {
        return array(
            
            array('value'=>'1', 'label'=>Mage::helper('ExtraConfig')->__('Enable')),
            array('value'=>'2', 'label'=>Mage::helper('ExtraConfig')->__('Enable with Quickview')), 
            array('value'=>'3', 'label'=>Mage::helper('ExtraConfig')->__('Disable'))     
        );
    }

}?>