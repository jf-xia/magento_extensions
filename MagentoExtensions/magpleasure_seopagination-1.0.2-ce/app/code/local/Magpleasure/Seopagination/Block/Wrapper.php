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

class Magpleasure_Seopagination_Block_Wrapper extends Mage_Core_Block_Template
{
    protected $_productList = null;

    /**
     * Helper
     *
     * @return Magpleasure_Seopagination_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('seopagination');
    }


    public function canShow()
    {
        return $this->_helper()->confRelNextPrev();
    }

    /**
     * @return Magpleasure_Seopagination_Block_Catalog_Product_List
     */
    protected function _getFakeProductList()
    {
        if (!$this->_productList){
            $list = Mage::app()->getLayout()->getBlock('product_list');
            if ($list){
                $list->toHtml();
                $this->_productList = $list;
            }
        }
        return $this->_productList;
    }

    /**
     * Pager
     *
     * @return Magpleasure_Seopagination_Block_Page_Html_Pager|boolean
     */
    protected function _getPager()
    {
        if ($pager = $this->_getFakeProductList()->getToolbarBlock()->getChild('product_list_toolbar_pager')){
            if ($pager->getCollection()){
                return $pager;
            }
        }
        return false;
    }

    public function getNextUrl()
    {
        if ($pager = $this->_getPager()){
            if (!$pager->isLastPage()){
                return $pager->getNextPageUrl();
            }
        }
        return "";
    }

    public function getPreviousUrl()
    {
        if ($pager = $this->_getPager()){
            if (!$pager->isFirstPage()){
                return $pager->getPreviousPageUrl();
            }
        }
        return "";
    }
}