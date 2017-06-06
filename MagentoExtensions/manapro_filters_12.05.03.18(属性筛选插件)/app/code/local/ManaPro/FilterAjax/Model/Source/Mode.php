<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterAjax
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

class ManaPro_FilterAjax_Model_Source_Mode extends Mana_Core_Model_Source_Abstract {
	protected function _getAllOptions() {
		return array(
            array('value' => ManaPro_FilterAjax_Model_Mode::OFF, 'label' => Mage::helper('manapro_filterajax')->__('No')),
            array('value' => ManaPro_FilterAjax_Model_Mode::ON_FOR_ALL, 'label' => Mage::helper('manapro_filterajax')->__('Yes')),
            array('value' => ManaPro_FilterAjax_Model_Mode::ON_FOR_USERS, 'label' => Mage::helper('manapro_filterajax')->__('Yes for Users, No for Search Bots (Listed Below)')),
        );
	}
}