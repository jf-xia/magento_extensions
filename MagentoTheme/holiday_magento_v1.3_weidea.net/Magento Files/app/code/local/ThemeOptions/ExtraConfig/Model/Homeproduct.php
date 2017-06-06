<?php class ThemeOptions_ExtraConfig_Model_Homeproduct
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'new', 'label'=>Mage::helper('ExtraConfig')->__('New Product')),
            array('value'=>'featured', 'label'=>Mage::helper('ExtraConfig')->__('Featured Product')),
            array('value'=>'sale', 'label'=>Mage::helper('ExtraConfig')->__('ON Sale Product')) 
            
        );
    }

}?>