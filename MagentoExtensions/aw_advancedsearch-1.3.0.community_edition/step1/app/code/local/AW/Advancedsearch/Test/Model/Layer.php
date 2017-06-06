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

/**
 * phpunit --coverage-html ./report UnitTests
 */
class AW_Advancedsearch_Test_Model_Layer extends EcomDev_PHPUnit_Test_Case
{
    /**
    * Test of getting session model
    *
    * @test
    * @doNotIndexAll
    * @dataProvider dataProvider
    */
    public function prepareProductCollection($testId, $matches)
    {
        $model = Mage::getModel('awadvancedsearch/layer');
        $result = array('1' => true);
        if (!is_null($matches)) {
            $matches = explode(',', $matches);
            foreach($matches as $item) {
                $result['matches'][$item] = $item;
            }
        }
        Mage::unregister(AW_Advancedsearch_Helper_Data::SPHINX_SEARCH_RESULTS);
        Mage::register(AW_Advancedsearch_Helper_Data::SPHINX_SEARCH_RESULTS, $result);
        $collection = $model->prepareProductCollection(null);
		$expected = $this->expected('id' . $testId);
        $isInString = strpos(strtolower($collection->getSelect()->__toString()), $expected->getPartOfQuery()) !== FALSE;
		$this->assertTrue(
            $isInString == $expected->getResult()
        );
    }
}
