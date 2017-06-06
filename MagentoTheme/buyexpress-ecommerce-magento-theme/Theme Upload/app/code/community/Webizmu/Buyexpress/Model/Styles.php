<?php
class Webizmu_Buyexpress_Model_Styles
{
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('buyexpress')->__('Red')),
            array('value'=>2, 'label'=>Mage::helper('buyexpress')->__('Blue')),
                           
        );
    }
}


