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
 * @package    Magpleasure_Seopagination
 * @version    1.0.2
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Seopagination_Helper_Data extends Mage_Core_Helper_Abstract
{
    const URL_SUFFIX = ".html";

    const REGISTRY_ACTIVITY_FLAG = 'seopagination_route_activity';

    /**
     * Url Model
     *
     * @return Magpleasure_Seopagination_Model_Url
     */
    public function _url()
    {
        return Mage::getModel('seopagination/url');
    }

    /**
     * Magpleasure Common Helper
     *
     * @return Magpleasure_Common_Helper_Data
     */
    public function getCommon()
    {
        return Mage::helper('magpleasure');
    }

    public function confEnabled()
    {
        return  !!Mage::getStoreConfig('seobundle/pagination/enabled') &&
                !!$this->isModuleOutputEnabled('Magpleasure_Seopagination');
    }

    public function confRelNoFollow()
    {
        return $this->confEnabled() && !!Mage::getStoreConfig('seobundle/pagination/nofollow');
    }

    public function confRelNextPrev()
    {
        return $this->confEnabled() && Mage::getStoreConfig('seobundle/pagination/relnextprev');
    }

    public function confSeoPages()
    {
        return $this->confEnabled() && Mage::getStoreConfig('seobundle/pagination/seo_pages');
    }

    public function confOnlyFirstIsDescribed()
    {
        return $this->confEnabled() && Mage::getStoreConfig('seobundle/pagination/description_on_first');
    }

    public function getUrlSuffix()
    {
        return $this->getUrlCategorySuffix();
    }

    public function getUrlCategorySuffix()
    {
        return Mage::getStoreConfig('catalog/seo/category_url_suffix');
    }

    public function getActivityFlag()
    {
        return self::REGISTRY_ACTIVITY_FLAG;
    }
}