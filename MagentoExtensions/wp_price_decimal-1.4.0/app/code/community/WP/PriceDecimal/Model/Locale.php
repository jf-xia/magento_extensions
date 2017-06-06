<?php

class WP_PriceDecimal_Model_Locale extends Mage_Core_Model_Locale
{
    /**
     * Functions returns array with price formating info for js function
     * formatCurrency in js/varien/js.js
     *
     * @return array
     */
    public function getJsPriceFormat()
    {
        $result = parent::getJsPriceFormat();
        $params = Mage::helper('pricedecimal')->getParams();
        if (!$params['skeep']) $result['precision'] = $result['requiredPrecision'] = $params['precision'];
        return $result;
    }
}
