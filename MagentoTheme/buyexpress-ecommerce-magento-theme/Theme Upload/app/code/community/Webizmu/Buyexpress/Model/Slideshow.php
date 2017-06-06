<?php
class Webizmu_Buyexpress_Model_Slideshow
{
    public function toOptionArray()
    {
		return array(
			array('value'=>1, 'label'=>Mage::helper('buyexpress')->__('')),
			array('value'=>2, 'label'=>Mage::helper('buyexpress')->__('Image')),
            array('value'=>3, 'label'=>Mage::helper('buyexpress')->__('Video')),
			array('value'=>4, 'label'=>Mage::helper('buyexpress')->__('HTML')),
                           
        );
    }
}
