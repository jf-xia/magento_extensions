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
 * @package    Magpleasure_Vkomments
 * @version    1.0
 * @copyright  Copyright (c) 2011 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */
class Magpleasure_Vkomments_Block_Comments extends Mage_Core_Block_Template
{
    public function getApiId()
    {
        return Mage::getStoreConfig('vkomments/general/api_id');
    }

    public function getApiKey()
    {
        return Mage::getStoreConfig('vkomments/general/api_key');
    }

    public function canShow()
    {
        return (!!$this->getApiId() && !!$this->getApiKey() && !!$this->getProductId());
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
        return Mage::getStoreConfig('vkomments/general/width') ? Mage::getStoreConfig('vkomments/general/width') : 500;
    }

    public function getLimit()
    {
        return Mage::getStoreConfig('vkomments/general/limit') ? Mage::getStoreConfig('vkomments/general/limit') : 15;
    }
    
}
