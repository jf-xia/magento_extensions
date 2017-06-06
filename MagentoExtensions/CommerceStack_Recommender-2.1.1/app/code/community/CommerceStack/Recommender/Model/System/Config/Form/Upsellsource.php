<?php
class CommerceStack_Recommender_Model_System_Config_Form_Upsellsource 
{  
    public function toOptionArray()
    {
        return array(
        array('value' => 'crosssell', 'label' => Mage::helper('core')->__('Cross-sell')),  
        array('value' => 'related', 'label' => Mage::helper('core')->__('Related Products')),
        array('value' => 'random', 'label' => Mage::helper('core')->__('Random')),
        );
    }
}