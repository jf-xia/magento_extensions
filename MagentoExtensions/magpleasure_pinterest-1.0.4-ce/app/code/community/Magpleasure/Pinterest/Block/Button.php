<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Pinterest
 * @version    1.0.4
 * @copyright  Copyright (c) 2012 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */


class Magpleasure_Pinterest_Block_Button extends Mage_Core_Block_Template
{
    protected $_isIntegration = false;

    public function isIntegration()
    {
        $this->_isIntegration = true;
        return $this;
    }

    public function isEnabled()
    {
        return Mage::getStoreConfig('mppinterest/general/enabled');
    }

    public function canShow()
    {
        if ($this->_isIntegration){
            return (Mage::getStoreConfig('mppinterest/general/display_in_other') && $this->isEnabled() && !!$this->getProduct());
        } else {
            return ($this->isEnabled() && !!$this->getProduct());
        }
    }

    /**
     * Product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if ($product = Mage::registry('current_product')){
            return $product;
        }
        return false;
    }

    /**
     * Retrieves Canonical Url
     *
     * @return string
     */
    public function getCanonicalUrl()
    {
        $params = array();
        if (Mage::helper('catalog/product')->canUseCanonicalTag()){
            $params = array('_ignore_category'=>true);
        }
        /** @var Mage_Catalog_Model_Product $product  */
        $product = $this->getProduct();
        return $product->getUrlModel()->getUrl($product, $params);
    }

    public function getProductUrl()
    {
        return urlencode($this->getCanonicalUrl());
    }

    public function getAddPrice()
    {
        return Mage::getStoreConfig('mppinterest/general/add_price');
    }

    public function getButtonType()
    {
        return Mage::getStoreConfig('mppinterest/general/button_type');
    }

    public function getShortDescription()
    {
        $shortDescription = strip_tags($this->getProduct()->getShortDescription());
        if ($this->getAddPrice()){
            $price = str_replace("USD", "$", Mage::app()->getStore()->formatPrice($this->getProduct()->getFinalPrice(), false));
            $shortDescription = trim($shortDescription).$this->__(" Price %s", $price);
        }
        return urlencode($shortDescription);
    }

    public function getPinItUrl()
    {
        $productUrl = $this->getProductUrl();
        $shortDescription = $this->getShortDescription();
        $imageUrl = urlencode($this->helper('catalog/image')->init($this->getProduct(), 'image')->__toString());
        $url = "http://pinterest.com/pin/create/button/?url={$productUrl}&media={$imageUrl}&description={$shortDescription}";
        return $url;
    }
}
