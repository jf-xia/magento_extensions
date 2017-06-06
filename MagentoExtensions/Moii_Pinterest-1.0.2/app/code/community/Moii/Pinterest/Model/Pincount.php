<?php
class Moii_Pinterest_Model_Pincount
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'horizontal', 'label'=>Mage::helper('Moii_Pinterest')->__('Horizontal')),
            array('value'=>'vertical', 'label'=>Mage::helper('Moii_Pinterest')->__('Vertical')),
            array('value'=>'none', 'label'=>Mage::helper('Moii_Pinterest')->__('No Count')),                  
        );
    }

}
?>