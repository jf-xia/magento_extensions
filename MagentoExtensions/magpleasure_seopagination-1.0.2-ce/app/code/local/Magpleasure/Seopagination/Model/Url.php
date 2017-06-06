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

class Magpleasure_Seopagination_Model_Url extends Mage_Core_Model_Url
{
    const MODE_PARAM = "All";
    const MODE_SEPARATOR = "-";

    /** @var Varien_Object */
    protected $_params = null;
    protected $_category = null;

    protected function _construct()
    {
        parent::_construct();
        $this->_params = new Varien_Object($this->getRequest()->getParams());
    }

    /**
     * Helper
     *
     * @return Magpleasure_Seopagination_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('seopagination');
    }

    protected function _getPageVarName()
    {
        return Mage::getBlockSingleton('page/html_pager') ? Mage::getBlockSingleton('page/html_pager')->getPageVarName() : 'p';
    }

    /**
     * Category Model
     *
     * @return Mage_Catalog_Model_Category
     */
    protected function _getCategory()
    {
        if (!$this->_category) {
            $category = Mage::registry('current_category');
            $this->_category = $category;
        }
        return $this->_category;
    }

    public function canUse()
    {
        return !!$this->_getCategory();
    }

    protected function _getPagePath($page = null)
    {
        $pagePath = "";
        if (!$page) {
            $page = $this->_params->getData('_page') ? $this->_params->getData('_page') : 1;
        }

        if ($page > 1) {
            $pagePath = "/{$page}";
        }
        return $pagePath;
    }

    protected function _getQueryString()
    {
        $params = array();
        foreach ($this->_params->getData() as $key => $value) {

            if ($this->_helper()->getCommon()->getMagento()->getModuleVersion('Magentix_RewritingFilters')) {
                if (in_array($key, array(
                    "dir",
                    "order",
                    "mode",
                ))) {
                    $params[$key] = $value;
                }
            } else {

                if ((strpos($key, "_") !== 0) &&
                    ($key != "id") &&
                    ($key != $this->_getPageVarName())
                ) {
                    $params[$key] = $value;
                }

            }
        }

        if (count($params)) {
            return http_build_query($params);
        } else {
            return "";
        }
    }

    /**
     * Prepare SEO Url
     *
     * @param array $params
     * @return string
     */
    public function getCategoryUrl($params = array())
    {
        $this->_params->addData($params);
        $baseUrl = Mage::getBaseUrl('web');


        if ($this->_helper()->getCommon()->getMagento()->getModuleVersion('Magentix_RewritingFilters')){

            /** @var $urlModel Magentix_RewritingFilters_Model_Url */
            $urlModel = Mage::getModel('rewritingfilters/url');

            $magentixUrl = $urlModel->getCategoryUrl();
            $magentixUrl = str_replace($baseUrl, "", $magentixUrl);

            $urlPath = str_replace($this->_helper()->getUrlCategorySuffix(), "", $magentixUrl);

        } else {

            $urlPath = str_replace($this->_helper()->getUrlCategorySuffix(), "", $this->_getCategory()->getUrlPath());
        }

        $pagePath = "";
        $filterPath = "";
        $queryStr = $this->_getQueryString() ? "?" . $this->_getQueryString() : "";
        $pagePath = $this->_getPagePath();
        return $baseUrl . $urlPath . $filterPath . $pagePath . $this->_helper()->getUrlSuffix() . $queryStr;
    }

    public function responseUrl($url, $page = 1)
    {
        # 1. Create piece of URL
        $pagePath = "";
        $filterPath = "";

        $pagePath = $this->_getPagePath($page);
        $endOfUrl = $filterPath . $pagePath . $this->_helper()->getUrlSuffix();

        # 2. Remove it from string
        return str_replace($endOfUrl, "", $url) . $this->_helper()->getUrlCategorySuffix();
    }

    /**
     * Response Current Page
     *
     * @param string $url
     * @return int|boolean
     */
    public function responsePage($url)
    {
        $pattern = "/\/([\d]{1,}){$this->_helper()->getUrlSuffix()}$/i";
        preg_match_all($pattern, $url, $matches);
        if (count($matches[1])) {
            $page = $matches[1][0];
            if ($page > 1) {
                return (int)$page;
            }
        }
        return false;
    }

}