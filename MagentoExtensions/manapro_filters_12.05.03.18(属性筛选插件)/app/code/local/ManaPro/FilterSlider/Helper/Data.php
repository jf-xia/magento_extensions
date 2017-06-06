<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterSlider
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/* BASED ON SNIPPET: New Module/Helper/Data.php */
/**
 * Generic helper functions for ManaPro_FilterSlider module. This class is a must for any module even if empty.
 * @author Mana Team
 */
class ManaPro_FilterSlider_Helper_Data extends Mage_Core_Helper_Abstract {
	public function getUrl($name) {
		$query = array(
            $name=>'__0__,__1__',
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        return Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
	}
	public function getClearUrl($name) {
		$query = array(
            $name=>null,
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        return Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
	}
}