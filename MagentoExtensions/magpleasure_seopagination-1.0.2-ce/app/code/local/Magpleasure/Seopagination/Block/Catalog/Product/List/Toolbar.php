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

class Magpleasure_Seopagination_Block_Catalog_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    const CACHE_PREFIX = 'MP_SEO_BNDL_PAG_TLB_';

    protected $_noFollowUrls = array();

    /**
     * Helper
     *
     * @return Magpleasure_Seopagination_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('seopagination');
    }

    /**
     * Return current URL with rewrites and additional parameters
     *
     * @param array $params Query parameters
     * @return string
     */
    public function getPagerUrl($params=array())
    {
        if (isset($params['p'])){
            $params['_page'] = $params['p'];
        }

        return $this->_helper()->_url()->getCategoryUrl($params);
    }

    public function getOrderUrl($order, $direction)
    {
        $url = parent::getOrderUrl($order, $direction);
        $this->_noFollowUrls[] = $url;
        return $url;
    }

    public function getLimitUrl($limit)
    {
        $url = parent::getLimitUrl($limit);
        $this->_noFollowUrls[] = $url;
        return $url;
    }

    public function getModeUrl($mode)
    {
        $url = parent::getModeUrl($mode);
        $this->_noFollowUrls[] = $url;
        return $url;
    }

    protected function _getCategoryId()
    {
        if (Mage::registry('current_category')){
            return Mage::registry('current_category')->getId();
        } else {
            return "NO";
        }
    }

    protected function _getCacheKey()
    {
        $params = array(
            http_build_query($this->getRequest()->getParams()),
            Mage::app()->getStore()->getId(),
            $this->getCollection()->getSize(),
            $this->_getCategoryId(),
        );

        return self::CACHE_PREFIX.md5(implode('_', $params));
    }

    protected function _toHtml()
    {
        Varien_Profiler::start('mp::seopagination::generate_toolbar');
        $cacheKey = $this->_getCacheKey();
        if (!($html = $this->_helper()->getCommon()->getCache()->getPreparedHtml($cacheKey))){
            $html = parent::_toHtml();
            if ($this->_helper()->confRelNextPrev()){

                try {
                    $dom = Mage::helper('seopagination/tools_simpledom')->str_get_html($html);
                    foreach ($this->_noFollowUrls as $noFollowUrl){
                        foreach ($dom->find("a[href={$noFollowUrl}]") as $element){
                            $element->setAttribute('rel', 'nofollow');
                        }
                    }
                    $html = $dom->__toString();

                } catch (Exception $e){
                    $this->_helper()->getCommon()->getException()->logException($e);
                }
            }

            $this->_helper()->getCommon()->getCache()->savePreparedHtml($cacheKey, $html);
        }

        Varien_Profiler::stop('mp::seopagination::generate_toolbar');

        return $html;
    }

}