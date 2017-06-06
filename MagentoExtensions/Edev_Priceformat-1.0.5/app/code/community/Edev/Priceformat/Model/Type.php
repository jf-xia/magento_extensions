<?php
class Edev_Priceformat_Model_Type
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Symbol')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Short name')),
			array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Long name')),
        );
    }

}