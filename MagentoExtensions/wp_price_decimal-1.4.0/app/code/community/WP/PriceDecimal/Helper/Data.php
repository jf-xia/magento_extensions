<?php

class WP_PriceDecimal_Helper_Data extends Mage_Core_Helper_Abstract
{
    public static function getParams()
    {
        $config         = Mage::getStoreConfig('price_decimal/general');
        $moduleName     = Mage::app()->getRequest()->getModuleName();
        $id             = Mage::app()->getRequest()->getParam('id', '');

        $skeep          = !$config['enabled'];

        if ($moduleName == 'checkout' && $config['exclude_cart'] && !$id) $skeep = true;
        if ($moduleName == 'admin') $skeep = true; // --- if Backand skeep

        $precision = 0;
        if (isset($config['precision']) && ($config['precision'] + 0) >= 0)
            $precision = $config['precision'] + 0;

        return array(
            'skeep'     => $skeep,
            'precision' => $precision,
        );
    }

    public static function trimZeroRight($price, $precision)
    {
        if (!Mage::getStoreConfig('price_decimal/general/trim_zero_right'))
            return $precision;
        $xPrice = sprintf("%." . $precision . "F", $price);
        $decimal = strrchr($xPrice, '.');
        $c1 = strlen($decimal);
        $decimal = rtrim($decimal, '0');
        $c2 = strlen($decimal);
        $xPrecision = $precision - ($c1 - $c2);
        return $xPrecision;
    }
}
