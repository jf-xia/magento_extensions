<?php
class Webizmu_Buyexpress_Model_Banners
{
    public function toOptionArray()
    {
		return array(
			array('value'=>1, 'label'=>Mage::helper('buyexpress')->__('Homepage Only')),
            array('value'=>2, 'label'=>Mage::helper('buyexpress')->__('All Pages')),
                           
        );
    }
}


