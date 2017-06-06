<?php
/** 
 * @category    Mana
 * @package     ManaPro_FilterSuperSlider
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/**
 * Generic helper functions for ManaPro_FilterSuperSlider module. This class is a must for any module even if empty.
 * @author Mana Team
 */
class ManaPro_FilterSuperSlider_Helper_Data extends Mage_Core_Helper_Abstract {
    public function formatNumber($number, $options) {
        if ($options->getSliderThreshold() && $number >= $options->getSliderThreshold()) {
            $format = sprintf('01.%dF', $options->getSliderDecimalDigits2());
            $number = sprintf('%'. $format, Mage::app()->getLocale()->getNumber(round($number / $options->getSliderThreshold(), $options->getSliderDecimalDigits2())));
            return (string)($options->getSliderNumberFormat2() ? str_replace('0', $number, $options->getSliderNumberFormat2()) : $number);
        }
        else {
            $format = sprintf('01.%dF', $options->getSliderDecimalDigits());
            $number = sprintf('%' . $format, Mage::app()->getLocale()->getNumber($number));
            return (string)($options->getSliderNumberFormat() ? str_replace('0', $number, $options->getSliderNumberFormat()) : $number);
        }
    }
    public function beforeInput($options) {
        return mb_substr($options->getSliderNumberFormat(), 0, mb_strpos($options->getSliderNumberFormat(), '0'));
    }
    public function afterInput($options) {
        return mb_substr($options->getSliderNumberFormat(), mb_strpos($options->getSliderNumberFormat(), '0') + 1);
    }
    public function getAttributeUrl($name) {
        $query = array(
            $name => '__0__',
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        return Mage::getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true, '_query' => $query));
    }
}