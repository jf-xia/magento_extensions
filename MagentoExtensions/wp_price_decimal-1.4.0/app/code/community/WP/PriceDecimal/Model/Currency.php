<?php

class WP_PriceDecimal_Model_Currency extends Mage_Directory_Model_Currency
{
    /**
    * Format price to currency format
    *
    * @param   double $price
    * @param   bool $includeContainer
    * @return  string
    */
    public function format($price, $options=array(), $includeContainer = true, $addBrackets = false)
    {
        $params = Mage::helper('pricedecimal')->getParams();
        if ($params['skeep']) return parent::format($price, $options, $includeContainer, $addBrackets);
        $params['precision'] = Mage::helper('pricedecimal')->trimZeroRight($price, $params['precision']);
        return $formatPrice = $this->formatPrecision($price, $params['precision'], $options, $includeContainer, $addBrackets);
    }
}
