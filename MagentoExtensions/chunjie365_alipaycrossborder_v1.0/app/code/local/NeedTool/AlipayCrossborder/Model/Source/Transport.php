<?php

class NeedTool_AlipayCrossborder_Model_Source_Transport
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'https', 'label' => Mage::helper('alipaycrossborder')->__('https')),
            array('value' => 'http', 'label' => Mage::helper('alipaycrossborder')->__('http')),
        );
    }
}