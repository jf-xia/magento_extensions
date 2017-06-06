<?php
/**
 * ECGiken_Price
 *
 * Copyright (c) 2011, EC-GIKEN Inc.
 * All rights reserved.
 */

class ECGiken_Price_Model_Directory_Currency extends Mage_Directory_Model_Currency {

    public function formatTxt($price, $options = array()) {
        $options = Mage::helper('ecgprice')->addOptions($options);
        return parent::formatTxt($price, $options);
    }

    public function formatSimpleTxt($price, $options = array()) {
        $simple = $this->formatTxt($price, $options);
        return preg_replace('/\.0+$/', '', preg_replace('/[^0-9\.]/', '', $simple));
    }
}
