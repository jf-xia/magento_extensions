<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
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
 * @package    AW_Advancedsearch
 * @version    1.3.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Advancedsearch_Model_Layer extends Mage_Catalog_Model_Layer
{

    public function getProductCollection()
    {
        $categoryId = $this->getCurrentCategory()->getId();
        if (isset($this->_productCollections[$categoryId])) {
            $collection = $this->_productCollections[$categoryId];
        } else {
            $collection = $this->prepareProductCollection(null);
            $this->_productCollections[$categoryId] = $collection;
        }
        return $collection;
    }

    public function prepareProductCollection($collection)
    {
        $index = Mage::helper('awadvancedsearch/catalogsearch')->getResults(AW_Advancedsearch_Model_Source_Catalogindexes_Types::CATALOG);
        if ($index) {
            $collection = $index->getResults();

            //parent::prepareProductCollection($collection);
            /* big load time on CE1411 */

            $collection
                    ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                    ->addMinimalPrice()
                    ->addFinalPrice()
                    ->addTaxPercents()
                    ->addUrlRewrite($this->getCurrentCategory()->getId())
            ;

            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);

            $visibility = Mage::getSingleton('catalog/product_visibility')->getVisibleInSearchIds();
            $collection->addAttributeToSelect('visibility');
            $collection->addAttributeToFilter('visibility', array('in' => $visibility));
        } else {
            $collection = Mage::getModel('catalog/product')->getCollection();
            $collection->addAttributeToFilter('entity_id', array('in' => -1));
        }
        return $collection;
    }

}
