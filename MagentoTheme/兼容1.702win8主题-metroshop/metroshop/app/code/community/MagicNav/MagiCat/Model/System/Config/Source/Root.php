<?php

class MagicNav_MagiCat_Model_System_Config_Source_Root
	extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	protected $_options;

    public function toOptionArray()
    {
		if (! isset($this->_options))
		{
			$options = array(
				array(
					'label' => Mage::helper('magicat')->__('Current category and sub category'),
					'value' => 'current',
				),
				array(
					'label' => Mage::helper('magicat')->__('all category'),
					'value' => 'root',
				),
			);
			$this->_options = $options;
		}
		return $this->_options;
    }

    public function getAllOptions()
    {
    	return $this->toOptionArray();
    }
}