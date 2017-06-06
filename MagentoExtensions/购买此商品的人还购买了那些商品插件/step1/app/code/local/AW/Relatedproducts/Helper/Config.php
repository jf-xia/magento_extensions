<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Relatedproducts
 * @version    1.4.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */


class AW_Relatedproducts_Helper_Config
{
    const EXTENSION_KEY = 'relatedproducts';
    const GENERAL_SAME_CATEGORY = 'general/same_category';
    const GENERAL_PRODUCTS_TO_DISPLAY = 'general/products_to_display';
    const CHECKOUT_ENABLED = 'checkout_block/enabled';

    public function getConfig($key, $store = null)
    {
        return Mage::getStoreConfig(self::EXTENSION_KEY . '/' . $key, $store);
    }

    public function getGeneralSameCategory($store = null)
    {
        return $this->getConfig(self::GENERAL_SAME_CATEGORY, $store);
    }

    public function getGeneralProductsToDisplay($store = null)
    {
        return $this->getConfig(self::GENERAL_PRODUCTS_TO_DISPLAY, $store);
    }

    public function getCheckoutBlockEnabled($store = null)
    {
        return $this->getConfig(self::CHECKOUT_ENABLED, $store);
    }

    /**
     * getGeneral_DisableNotifications - calls getConfig('general/disable_notifications')
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        //TODO add stores support
        if (strpos($name, 'get') === 0 && strpos($name, '_') !== false) {
            $name = substr($name, 3);
            list($fieldset, $option) = explode('_', $name);
            $fieldset = strtolower($fieldset);
            $option = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $option));
            return $this->getConfig($fieldset . '/' . $option);
        }
    }
}
