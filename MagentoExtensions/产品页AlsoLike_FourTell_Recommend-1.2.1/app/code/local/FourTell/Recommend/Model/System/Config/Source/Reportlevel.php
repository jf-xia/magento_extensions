<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * Generate options for Report Level selectbox in system config
 * 
 */
class FourTell_Recommend_Model_System_Config_Source_Reportlevel
{
    public function toOptionArray()
    {
		return array(
            'Error'			=> Mage::helper('recommend')->__('Error'),
            'Warning'		=> Mage::helper('recommend')->__('Warning'),
            'Information'	=> Mage::helper('recommend')->__('Information'),
            'All'			=> Mage::helper('recommend')->__('All'),
            'None'			=> Mage::helper('recommend')->__('None'),
        );
    }
}
