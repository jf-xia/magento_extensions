<?php
class Moii_Pinterest_Model_Enable
{
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('Moii_Pinterest')->__('Yes')),
            array('value'=>0, 'label'=>Mage::helper('Moii_Pinterest')->__('No')),              
        );
    }

}
?>