<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file PRICE COUNTDOWN-LICENSE.txt.
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento 1.4.x and 1.5.x COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento 1.4.x and 1.5.x COMMUNITY edition.
 * =================================================================
 */

class Apptha_Timer_Block_List extends Mage_Catalog_Block_Product_List
{
    protected function _prepareLayout()
    {
        $block = $this->getLayout()->getBlock('product_list');
        if ($block) {
            $block->setTemplate('timer/list.phtml');
        }

        $block = $this->getLayout()->getBlock('search_result_list');
        if ($block) {
            $block->setTemplate('timer/list.phtml');
        }
    }
}
?>