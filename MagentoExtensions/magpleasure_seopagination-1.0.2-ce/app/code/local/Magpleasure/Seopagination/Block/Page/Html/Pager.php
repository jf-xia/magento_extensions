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

class Magpleasure_Seopagination_Block_Page_Html_Pager extends Mage_Page_Block_Html_Pager
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

    public function getPageUrl($page)
    {
        if ($this->_helper()->confSeoPages()){
            $urlModel = $this->_helper()->_url();
            return $urlModel->getCategoryUrl(array(
                '_page' => $page,
                $this->getPageVarName() => null,
            ));

        } else {
            return parent::getPageUrl($page);
        }
    }

    protected function _toHtml()
    {
        $html = parent::_toHtml();
        if ($this->_helper()->confRelNextPrev()){
            try {
                $dom = Mage::helper('seopagination/tools_simpledom')->str_get_html($html);

                if (!$this->isLastPage()){
                    $nextUrl = $this->getNextPageUrl();
                    foreach ($dom->find("a[href={$nextUrl}]") as $element){
                        $element->setAttribute('rel', 'next');
                    }
                }

                if (!$this->isFirstPage()){
                    $prevUrl = $this->getPreviousPageUrl();
                    foreach ($dom->find("a[href={$prevUrl}]") as $element){
                        $element->setAttribute('rel', 'prev');
                    }
                }
                return $dom->__toString();
            } catch (Exception $e){
                return $html;
            }
        } else {
            return $html;
        }
    }
}