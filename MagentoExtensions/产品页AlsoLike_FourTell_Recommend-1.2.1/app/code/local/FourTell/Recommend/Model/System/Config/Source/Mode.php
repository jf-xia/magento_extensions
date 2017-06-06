<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * Generate options for Mode selectbox in system config
 * 
 */
class FourTell_Recommend_Model_System_Config_Source_Mode
{
    public function toOptionArray()
    {
		return array(
			'Live'	=> Mage::helper('recommend')->__('Live'),
			'Test'	=> Mage::helper('recommend')->__('Test')
        );
	}
}
