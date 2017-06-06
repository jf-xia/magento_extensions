<?php
/**
 * ECGiken_Price
 *
 * Copyright (c) 2011, EC-GIKEN Inc.
 * All rights reserved.
 */

class ECGiken_Price_Model_Core_Locale extends Mage_Core_Model_Locale {

    public function getJsPriceFormat() {
        $result = parent::getJsPriceFormat();
        $result['presision'] = $result['requiredPrecision'] = Mage::helper('ecgprice')->getPrecision();
        return $result;
    }
}
