<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * Generate options for Report Level selectbox in system config
 * 
 */
class FourTell_Recommend_Model_System_Config_Source_Resell
{
    public function toOptionArray()
    {
		return array(
            '0'	=> Mage::helper('recommend')->__('No'),
            '1'	=> Mage::helper('recommend')->__('Yes'),
        );
    }
}
