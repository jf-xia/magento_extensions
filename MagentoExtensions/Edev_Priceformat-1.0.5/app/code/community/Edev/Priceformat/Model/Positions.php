<?php
class Edev_Priceformat_Model_Positions
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Left')),
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Right')),
        );
    }

}