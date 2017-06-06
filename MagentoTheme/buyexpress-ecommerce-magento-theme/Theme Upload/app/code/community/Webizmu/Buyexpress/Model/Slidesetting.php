<?php
class Webizmu_Buyexpress_Model_Slidesetting
{
    public function toOptionArray()
    {
		return array(
			array('value'=>1, 'label'=>Mage::helper('buyexpress')->__('ON')),
            array('value'=>2, 'label'=>Mage::helper('buyexpress')->__('OFF')),
                           
        );
    }
}


