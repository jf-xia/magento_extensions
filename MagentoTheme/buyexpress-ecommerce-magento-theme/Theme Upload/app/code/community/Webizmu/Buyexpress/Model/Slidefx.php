<?php
class Webizmu_Buyexpress_Model_Slidefx
{
    public function toOptionArray()
    {
		return array(
			array('value'=>1, 'label'=>Mage::helper('buyexpress')->__('Scroll Vertical')),
            array('value'=>2, 'label'=>Mage::helper('buyexpress')->__('Scroll Horizontal')),
                           
        );
    }
}


