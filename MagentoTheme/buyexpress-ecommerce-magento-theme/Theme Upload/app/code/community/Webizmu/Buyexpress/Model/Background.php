<?php
class Webizmu_Buyexpress_Model_Background
{
    public function toOptionArray()
    {
		return array(
			array('value'=>1, 'label'=>Mage::helper('buyexpress')->__('Image')),
            array('value'=>2, 'label'=>Mage::helper('buyexpress')->__('Color')),
                           
        );
    }
}


