<?php

class Magebuzz_Catsidebarnav_Model_System_Config_Position
{
    public function toOptionArray()
    {
        $options = array(
					array('value'=>'left','label'=> Mage::helper('catsidebarnav')->__('Left Sidebar')),
					/*array('value'=>'right','label'=> Mage::helper('catsidebarnav')->__('Right Sidebar')),*/
				);
		
		return $options;
    }
}