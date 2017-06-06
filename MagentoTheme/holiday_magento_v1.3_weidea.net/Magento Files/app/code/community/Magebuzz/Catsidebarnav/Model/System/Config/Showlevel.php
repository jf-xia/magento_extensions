<?php

class Magebuzz_Catsidebarnav_Model_System_Config_Showlevel
{
    public function toOptionArray()
    {
        $options = array(
					array('value'=>'all','label'=> Mage::helper('catsidebarnav')->__('All')),
					array('value'=>1,'label'=> Mage::helper('catsidebarnav')->__('1')),
					array('value'=>2,'label'=> Mage::helper('catsidebarnav')->__('2')),
					array('value'=>3,'label'=> Mage::helper('catsidebarnav')->__('3')),
					array('value'=>4,'label'=> Mage::helper('catsidebarnav')->__('4')),
				);
		
		return $options;
    }
}