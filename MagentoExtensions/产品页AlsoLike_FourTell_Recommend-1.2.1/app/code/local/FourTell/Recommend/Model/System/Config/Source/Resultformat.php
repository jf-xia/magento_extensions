<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * Generate options for Result Format selectbox in system config
 * 
 */
class FourTell_Recommend_Model_System_Config_Source_Resultformat
{
    public function toOptionArray()
    {
		return array(
            'TabDelimited'		=> Mage::helper('recommend')->__('Tab Delimited'),
            'CommaDelimited'	=> Mage::helper('recommend')->__('Comma Delimited'),
            'SpaceDelimited'	=> Mage::helper('recommend')->__('Space Delimited'),
            'XML'				=> Mage::helper('recommend')->__('XML'),
        );
    }
}
