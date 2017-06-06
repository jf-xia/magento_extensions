<?php

class Magestore_Groupdeal_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 0;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('groupdeal')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('groupdeal')->__('Disabled')
        );
    }
	
	static public function getOrderStatus()
    {
        return array(
            'processing'	=> Mage::helper('groupdeal')->__('Processing'),
            'complete'   	=> Mage::helper('groupdeal')->__('Complete'),
			'canceled'		=> Mage::helper('groupdeal')->__('Canceled'),
        );
    }
	
	static public function getStatusList(){
		return array(
			'0' => Mage::helper('groupdeal')->__('Disabled'),
			'1' => Mage::helper('groupdeal')->__('Enabled'),
			'2' => Mage::helper('groupdeal')->__('End'),
			'3' => Mage::helper('groupdeal')->__('Unreached'),
			'4' => Mage::helper('groupdeal')->__('Reached'),
			'5' => Mage::helper('groupdeal')->__('Opening'),
			'6' => Mage::helper('groupdeal')->__('Waiting'),
		);
	}
}