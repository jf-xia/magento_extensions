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
 * @package    Magpleasure_Facecomments
 * @version    1.0
 * @copyright  Copyright (c) 2011 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

/** Facebook Comments */
class Magpleasure_Facecomments_Block_Comments extends Mage_Core_Block_Template
{
    protected $_isGeneral = false;
    
    public function isEnabled()
    {
        return Mage::getStoreConfig('facecomments/general/enabled');
    }

    public function isGeneral()
    {
        $this->_isGeneral = true;
    }

    public function canShow()
    {
        if ($this->_isGeneral){
            return $this->isEnabled() && !!$this->getProductId();
        } else {
            return $this->isEnabled() && !!$this->getProductId() && Mage::getStoreConfig("facecomments/general/display_in_additional");
        }
    }

    public function getProductId()
    {
        if ($product = Mage::registry('current_product')){
            return sprintf('%s', $product->getId());
        }
        return false;
    }

    public function getWidth()
    {
        return Mage::getStoreConfig('facecomments/general/width') ? Mage::getStoreConfig('facecomments/general/width') : 500;
    }

    public function getLimit()
    {
        return Mage::getStoreConfig('facecomments/general/limit') ? Mage::getStoreConfig('facecomments/general/limit') : 15;
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
        $product = Mage::registry('current_product');
        return $product->getUrlModel()->getUrl($product, $params);
    }

}
