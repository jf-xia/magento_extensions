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

class AW_Advancedsearch_Model_Mysql4_Catalogindexes_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('awadvancedsearch/catalogindexes');
    }

    protected function _afterLoad()
    {
        foreach($this->getItems() as $item) {
            $item->callAfterLoad();
        }
        return parent::_afterLoad();
    }

    public function addTypeFilter($type)
    {
        $this->getSelect()->where('type = ?', $type);
        return $this;
    }

    public function addStatusFilter($active = true)
    {
        $this->getSelect()->where('status = ?', $active ? '1' : '0');
        return $this;
    }

    public function addExceptIdFilter($id = null)
    {
        if($id) {
            $this->getSelect()->where('id != ?', $id);
        }
        return $this;
    }

    public function addStoreFilter($stores = null, $breakOnAllStores = false) {
        $_stores = array(Mage::app()->getStore()->getId());
        if(is_string($stores)) $_stores = explode(',', $stores);
        if(is_array($stores)) $_stores = $stores;
        if(!in_array('0', $_stores))
            array_push($_stores, '0');
        if($breakOnAllStores && $_stores == array(0)) return $this;
        $_sqlString = '(';
        $i = 0;
        foreach($_stores as $_store) {
            $_sqlString .= sprintf('find_in_set(%s, store)', $this->getConnection()->quote($_store));
            if(++$i < count($_stores))
                $_sqlString .= ' OR ';
        }
        $_sqlString .= ')';
        $this->getSelect()->where($_sqlString);

        return $this;
    }

    public function addStateFilter($state = null)
    {
        $this->getSelect()->where('state = ?', $state ? $state : AW_Advancedsearch_Model_Source_Catalogindexes_State::READY);
        return $this;
    }

    public function setTypeOrder($dir = 'ASC')
    {
        $this->setOrder('type', $dir);
        return $this;
    }
}
