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


class AW_Relatedproducts_Helper_Data extends Mage_Core_Helper_Abstract
{
    /*
     * 	Take $relatedIds array and establish relations to each other
     */

    function updateRelations($relatedIds, $storeId = null)
    {
        if ($storeId === null) {
            $storeId = Mage::app()->getStore()->getId();
        }
        $model = Mage::getResourceModel('relatedproducts/relatedproducts');
        $arr = array();
        foreach ($relatedIds as $id) {
            //fetch relations for each of the ID's
            /** @var $model AW_Relatedproducts_Model_Relatedproducts */
            $model = Mage::getModel('relatedproducts/relatedproducts');
            $coll = $model->getCollection()
                ->addStoreFilter($storeId)
                ->addProductFilter($id)
                ->load();
            if (sizeof($coll) == 0) {
                foreach ($relatedIds as $i) {
                    if ($i != $id) //not the product for itself
                        $arr[$i] = 1; //set relation rate to 1 for all


                }
                $arr = serialize($arr);
                $model
                    ->setStoreId($storeId)
                    ->setProductId($id)
                    ->setRelatedArray($arr)
                    ->save();
            } else {
                foreach ($coll as $c) {
                    $incrementalId = $c->getId();
                    //take current related products
                    $arr = unserialize($c->getData('related_array'));
                    foreach ($relatedIds as $i) {
                        if ($i != $id) { //not the product for itself
                            if (!empty($arr[$i]))
                                $arr[$i] += 1; //increment the relation counter
                            else
                                $arr[$i] = 1;
                        }
                    }
                }
                $arr = serialize($arr);
                $model
                    ->setId($incrementalId)
                    ->setProductId($id)
                    ->setStoreId($storeId)
                    ->setRelatedArray($arr)
                    ->save();
            }
            $arr = array();
        }
    }

    public function isEnterprise()
    {
        return Mage::helper('awall/versions')->getPlatform() == AW_All_Helper_Versions::EE_PLATFORM;
    }

    public function checkVersion($version)
    {
        return version_compare(Mage::getVersion(), $version, '>=');
    }

    /**
     * Retrives Advanced Reviews Disabled Flag
     * @return boolean
     */
    public function getExtDisabled()
    {
        return Mage::getStoreConfig('advanced/modules_disable_output/AW_Relatedproducts');
    }

    /**
     *
     * @param <type> $storeId
     * @return array
     */
    public function getAllowStatuses($storeId = null)
    {
        $res = explode(",", Mage::getStoreConfig('relatedproducts/general/process_orders', $storeId));
        return count($res) ? $res : array(Mage_Sales_Model_Order::STATE_COMPLETE);
    }

    /**
     * @return AW_Relatedproducts_Helper_Config
     */
    public function _getConfigHelper()
    {
        return Mage::helper('relatedproducts/config');
    }

    public function isInstalledForProduct($productId, $storeId = null)
    {
        if ($storeId === null) {
            $storeId = Mage::app()->getStore()->getId();
        }
        /** @var $relatedCollection AW_Relatedproducts_Model_Mysql4_Relatedproducts_Collection */
        $relatedCollection = Mage::getModel('relatedproducts/relatedproducts')->getCollection();
        $relatedCollection->addProductFilter($productId)
            ->addStoreFilter($storeId);
        return ($relatedCollection->getSize() > 0);
    }

    /**
     * Retrives table name for Model Entity Name
     * @param string $modelEntity
     * @return string
     */
    public function getTableName($modelEntity)
    {
        try {
            $table = Mage::getSingleton('core/resource')->getTableName($modelEntity);
        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }
        return $table;
    }

    /**
     * Index sales data for current product
     * @param int|string $productId
     * @return AW_Relatedproducts_Block_Relatedproducts
     */
    public function installForProduct($productId, $storeId = null, $productsToDisplay = null)
    {
        $configHelper = $this->_getConfigHelper();
        $orders = Mage::getModel('sales/order')->getCollection();
        $orders->addAttributeToSelect('*')->addAttributeToFilter('status', array('in' => $this->getAllowStatuses()));
        if ($storeId === null) {
            $storeId = Mage::app()->getStore()->getId();
        }
        if ($productsToDisplay === null) {
            $productsToDisplay = $configHelper->getGeneralProductsToDisplay($storeId);
        }

        $catalogCategoryTable = $this->getTableName('catalog/category_product');
        if ($this->isEnterprise()) {
            $itemTable = $this->getTableName('sales/order_item');
            $orderAlias = 'main_table';
        } elseif ($this->checkVersion('1.4.1.0')) {
            $itemTable = $this->getTableName('sales/order_item');
            $orderAlias = 'main_table';
        } else {
            $itemTable = $orders->getTable('sales_flat_order_item');
            $orderAlias = 'e';
        }

        $orders->getSelect()->join(array('item' => $itemTable), $orderAlias . ".entity_id = item.order_id AND item.parent_item_id IS NULL", array())
            ->join(array('item1' => $itemTable), $orderAlias . ".entity_id = item1.order_id AND item1.parent_item_id IS NULL", array('i_count' => 'COUNT( item1.product_id )'))
            ->where($orderAlias . '.store_id = ?', $storeId)
            ->where('item.product_id = ?', $productId)
            ->group($orderAlias . '.entity_id')
            ->order('i_count DESC')
            ->limit($productsToDisplay);

        if ($configHelper->getGeneralSameCategory($storeId)) {
            $orders->getSelect()
            # Join cats of main product
                ->joinRight(array('mainProd' => $catalogCategoryTable), "mainProd.product_id = item.product_id", array())
            # Join cats of sub products
                ->joinLeft(array('subProd' => $catalogCategoryTable), "subProd.product_id = item1.product_id", array())
                ->where('mainProd.category_id = subProd.category_id');
        }

        $orders->load();

        $ids = array();

        foreach ($orders as $order) {
            $order = Mage::getModel('sales/order')->load($order->getId());
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
                $this->updateRelations($ids);
            }
        }
        return $this;
    }
}
