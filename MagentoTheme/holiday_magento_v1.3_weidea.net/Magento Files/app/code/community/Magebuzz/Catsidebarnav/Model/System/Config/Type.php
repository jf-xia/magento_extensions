<?php

class Magebuzz_Catsidebarnav_Model_System_Config_Type
{
    public function toOptionArray()
    {
        $options = array(
					array('value'=>'static','label'=> Mage::helper('catsidebarnav')->__('Static')),
					array('value'=>'click-2-click','label'=> Mage::helper('catsidebarnav')->__('Click to Click')),
					array('value'=>'fly-out','label'=> Mage::helper('catsidebarnav')->__('Fly Out')),
				);
		
		return $options;
    }
}