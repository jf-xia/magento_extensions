<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterAjax
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

class ManaPro_FilterAjax_Model_Source_Method extends Mana_Core_Model_Source_Abstract {
	protected function _getAllOptions() {
		return array(
            array('value' => ManaPro_FilterAjax_Model_Method::MARK_WITH_CSS_CLASS, 'label' => Mage::helper('manapro_filterajax')->__('Mark with CSS class')),
            array('value' => ManaPro_FilterAjax_Model_Method::WRAP_INTO_CONTAINER, 'label' => Mage::helper('manapro_filterajax')->__('Wrap into HTML element')),
        );
	}
}