<?php
class Moii_Pinterest_Model_Price
{
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('Moii_Pinterest')->__('Always')),
            array('value'=>2, 'label'=>Mage::helper('Moii_Pinterest')->__('Only Special Price')),              
            array('value'=>0, 'label'=>Mage::helper('Moii_Pinterest')->__('No')),              
        );
    }

}
?>