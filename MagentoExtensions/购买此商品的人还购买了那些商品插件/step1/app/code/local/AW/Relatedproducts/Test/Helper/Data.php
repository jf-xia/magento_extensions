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


class AW_Relatedproducts_Test_Helper_Data extends EcomDev_PHPUnit_Test_Case {

    public function setup() {

        AW_Relatedproducts_Test_Model_Mocks_Foreignresetter::dropForeignKeys();
        parent::setup();
    }

    /**
     * 
     * @test
     * @dataProvider provider__updateRalations  
     * @loadFixture
     *   
     */
    public function updateRelations($data) {

        if ($data['uid'] == '001') {
            $this->_processUpdateRelationsData($data);
        }

        if ($data['uid'] == '002') {
            $this->_processEmpty($data);
        }

        if (empty($data['relatedIds'])) {
            return Mage::helper('relatedproducts')->updateRelations(array());
        }
    }

    private function _processEmpty($data) {

        $collection = Mage::getModel('relatedproducts/relatedproducts')->getCollection();
        foreach ($collection as $item) {

            $item->delete();
        }

        Mage::helper('relatedproducts')->updateRelations($data['relatedIds']);
        $collection = Mage::getModel('relatedproducts/relatedproducts')->getCollection();
        $this->assertEquals($data['collectionCount'], $collection->count());

        foreach ($collection as $item) {

            $itemProduct = $item->getProductId();
            if (!isset($data['expected'][$itemProduct])) {
                $this->fail("Expected related on product {$itemProduct} has not been created");
            }

            $this->assertEquals($item->getRelatedArray(), $data['expected'][$itemProduct]);
        }
    }

    private function _processUpdateRelationsData($data) {

        $storeId = 1;
        Mage::app()->getStore()->setId($storeId);
        Mage::helper('relatedproducts')->updateRelations($data['relatedIds']);


        $collection = Mage::getModel('relatedproducts/relatedproducts')->getCollection()->addProductFilter(17)->addStoreFilter($storeId);
        $item = $collection->getFirstItem();
        $itemData = unserialize($item->getRelatedArray());


        foreach ($itemData as $key => $val) {

            $this->assertEquals($val, 2, 'Product ties should be increased by 1');
        }
        /* Now change store id to 2 and check that no product will be updated */

        Mage::app()->getStore()->setId(2);
        Mage::helper('relatedproducts')->updateRelations($data['relatedIds']);


        $collection = Mage::getModel('relatedproducts/relatedproducts')->getCollection()->addProductFilter(17)->addStoreFilter($storeId);
        $item = $collection->getFirstItem();
        $itemData = unserialize($item->getRelatedArray());


        foreach ($itemData as $key => $val) {
            $this->assertEquals($val, 2, 'Product ties should not be increased by 1');
        }
    }

    public function provider__updateRalations() {

        return array(
            array(array('relatedIds' => array(17, 27, 166), 'uid' => '001')),
            array(array('relatedIds' => array())),
            array(array('relatedIds' => array(17, 27), 'uid' => '002', 'expected' => array('17' => 'a:1:{i:27;i:1;}', '27' => 'a:1:{i:17;i:1;}'), 'collectionCount' => 2)),
        );
    }

}