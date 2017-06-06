<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Relatedproducts
 * @version    1.4.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */


class AW_Relatedproducts_Model_Observer
{
    protected function _getHelper($ext = '')
    {
        return Mage::helper('relatedproducts' . ($ext ? '/' . $ext : ''));
    }

    /**
     * Observe placing of new order
     * @param Varien_Object $observer
     */
    public function updateRelatedproductsOrderStatus($observer)
    {
        /** @var $helper AW_Relatedproducts_Helper_Data */
        $helper = $this->_getHelper();
        /** @var $order Mage_Sales_Model_Order */
        $order = $observer->getEvent()->getOrder();
        $storeId = $order->getStoreId();

        $oldStatus = $order->getOrigData('status');
        $newStatus = $order->getData('status');
        if (($oldStatus && $oldStatus != $newStatus)) {
            if (!in_array($newStatus, $helper->getAllowStatuses($storeId)) &&
                in_array($oldStatus, $helper->getAllowStatuses($storeId))
            ) {
                Mage::getModel('relatedproducts/relatedproducts')->getResource()->resetStatistics();
            }

        }
        if (!in_array($order->getStatus(), $helper->getAllowStatuses($storeId))) {
            return;
        }
        $ids = array();
        $items = $order->getAllItems();
        if (count($items)) {
            $ids = array();
            foreach ($items as $itemId => $item) {
                if (!$item->getParentItemId()) {
                    array_push($ids, $item->getProductId());
                }
            }
        }
        if (count($ids) > 1) {
            $helper->updateRelations($ids, $order->getStoreId());
        }
    }

    public function replaceCrossselsBlock($observer)
    {
        /** @var $layout Mage_Core_Model_Layout */
        $layout = Mage::app()->getLayout();
        /** @var $helper AW_Relatedproducts_Helper_Data */
        $helper = $this->_getHelper();
        /** @var $configHelper AW_Relatedproducts_Helper_Config */
        $configHelper = $this->_getHelper('config');
        if (!$helper->getExtDisabled() && $configHelper->getCheckoutBlockEnabled()) {
            /** @var $shoppingCartBlock Mage_Checkout_Block_Cart */
            $shoppingCartBlock = $layout->getBlock('checkout.cart');
            /** @var $wbtabBlock AW_Relatedproducts_Block_Relatedproducts */
            $wbtabBlock = $layout->createBlock('relatedproducts/relatedproducts')
                ->setTemplate('aw_relatedproducts/cartlist.phtml')
                ->setCheckoutMode();
            $shoppingCartBlock->setChild('crosssell', $wbtabBlock);
        }
    }
}
