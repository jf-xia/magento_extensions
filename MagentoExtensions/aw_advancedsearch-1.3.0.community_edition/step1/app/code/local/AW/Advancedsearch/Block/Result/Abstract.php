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

abstract class AW_Advancedsearch_Block_Result_Abstract extends Mage_Core_Block_Template
{
    protected $_collection;

    public function getResults()
    {
        if ($this->_collection === null) {
            $collection = $this->getData('index')->getResults();
            $this->_collection = $collection;
        }
        return $this->_collection;
    }

    protected function _getHelper($name = 'awadvancedsearch/results')
    {
        return Mage::helper($name);
    }

    abstract public function getPager();
}
