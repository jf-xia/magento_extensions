<?php
class Webizmu_Buyexpress_Model_Bgrepeat
{
    public function toOptionArray()
    {
		return array(
			array('value'=>1, 'label'=>Mage::helper('buyexpress')->__('Yes')),
            array('value'=>2, 'label'=>Mage::helper('buyexpress')->__('No')),
                           
        );
    }
}


