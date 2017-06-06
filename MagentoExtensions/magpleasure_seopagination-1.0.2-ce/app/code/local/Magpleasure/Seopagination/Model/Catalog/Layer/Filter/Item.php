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

class Magpleasure_Seopagination_Model_Catalog_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
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
     * Get filter item url
     *
     * @return string
     */
    public function getUrl()
    {
        if ($this->_helper()->confSeoPages()){
            return $this->_helper()->_url()->getCategoryUrl(array(
                        '_page'=>null,
                        $this->getFilter()->getRequestVar()=>$this->getValue(),
                        Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null,
                    ));
        } else {
            return parent::getUrl();
        }
    }

    /**
     * Get url for remove item from filter
     *
     * @return string
     */
    public function getRemoveUrl()
    {
        if ($this->_helper()->confSeoPages()){
            return $this->_helper()->_url()->getCategoryUrl(array(
                '_page'=>null,
                $this->getFilter()->getRequestVar()=>$this->getFilter()->getResetValue(),
                Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null,
            ));
        } else {
            return parent::getRemoveUrl();
        }
    }

}

