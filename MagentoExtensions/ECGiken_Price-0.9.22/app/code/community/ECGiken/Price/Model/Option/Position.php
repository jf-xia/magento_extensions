<?php
class ECGiken_Price_Model_Option_Position {

    private $_options;

    public function toOptionArray() {
        if (is_null($this->_options)) {
            $this->_options = array(
                array(
                    'label' => Mage::helper('ecgprice')->__('Please select position.'),
                    'value' => ""
                ),
                array(
                    'label' => Mage::helper('ecgprice')->__('Standard'),
                    'value' => 8
                ),
                array(
                    'label' => Mage::helper('ecgprice')->__('Right'),
                    'value' => 16
                ),
                array(
                    'label' => Mage::helper('ecgprice')->__('Left'),
                    'value' => 32
                )
            );
        }
        return $this->_options;
    }
}
