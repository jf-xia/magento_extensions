<?php
/**
 * ECGiken_Price
 *
 * Copyright (c) 2011, EC-GIKEN Inc.
 * All rights reserved.
 */

class ECGiken_Price_Helper_Data extends Mage_Core_Helper_Abstract {

    private $_add_options = null;
    private $_precision = null;

    public function addOptions($options = array()) {
        if (!$this->_add_options) {
            if (is_numeric(Mage::getStoreConfig('currency/format_options/precision'))) {
                $this->_add_options['precision'] = (int)Mage::getStoreConfig('currency/format_options/precision');
            } else {
                $this->_add_options['precision'] = 2;
            }
            if (strlen(Mage::getStoreConfig('currency/format_options/symbol')) > 0) {
                $this->_add_options['symbol'] = Mage::getStoreConfig('currency/format_options/symbol');
            }
            if (in_array(Mage::getStoreConfig('currency/format_options/symbol_position'), array(Zend_Currency::STANDARD, Zend_Currency::RIGHT, Zend_Currency::LEFT))) {
                $this->_add_options['position'] = (int)Mage::getStoreConfig('currency/format_options/symbol_position');
            }
        }
        return array_merge($options, $this->_add_options);
    }

    public function getPrecision() {
        if (!$this->_precision) {
            if (is_numeric(Mage::getStoreConfig('currency/format_options/precision'))) {
                $this->_precision = (int)Mage::getStoreConfig('currency/format_options/precision');
            } else {
                $this->_precision = 2;
            }
        }
        return $this->_precision;
    }
}
