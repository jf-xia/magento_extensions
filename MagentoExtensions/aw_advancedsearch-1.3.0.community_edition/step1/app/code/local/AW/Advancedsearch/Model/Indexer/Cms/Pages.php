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

class AW_Advancedsearch_Model_Indexer_Cms_Pages extends AW_Advancedsearch_Model_Indexer_Abstract
{
    const PRIMARY = 'page_id';

    protected $_indexTableModel = null;

    protected $_columns = array(
        self::PRIMARY => array(
            'type' => 'int(11)',
            'unsigned' => true,
            'is_null' => false,
            'default' => null,
            'extra' => null
        ),
        'title' => array(
            'type' => 'varchar(255)',
            'unsigned' => null,
            'is_null' => false,
            'default' => null,
            'extra' => null
        ),
        'content' => array(
            'type' => 'mediumtext',
            'unsigned' => null,
            'is_null' => false,
            'default' => null,
            'extra' => null
        ),
        '_updated' => array(
            'type' => 'tinyint',
            'unsigned' => true,
            'is_null' => false,
            'default' => null,
            'extra' => null
        )
    );

    protected function _getIndexName()
    {
        return 'cms_pages_';
    }

    protected function _getColumn($name = null)
    {
        return $name ? (isset($this->_columns[$name]) ? $this->_columns[$name] : null) : $this->_columns;
    }

    protected function _extendSqlAttributes(&$attributes)
    {
        array_unshift($attributes, self::PRIMARY);
        array_push($attributes, '_updated');
        parent::_extendSqlAttributes($attributes);
    }

    protected function _createTable()
    {
        $attributes = $this->_getExtendedAttributes();
        $queryString = '';
        foreach ($attributes as $attr) {
            $_columnSql = $this->_getColumnSql($attr);
            if ($_columnSql) {
                $queryString .= $_columnSql;
                if ($attr != $attributes[count($attributes) - 1]) {
                    $queryString .= ",\n";
                }
            }
        }
        return parent::_createTable($queryString);
    }

    protected function _getExcludedPages($stores)
    {
        $pageIds = array();
        $websiteRestrictions = Mage::helper('awadvancedsearch')->isExtensionInstalled('Enterprise_WebsiteRestriction');
        if ($stores == array(0)) {
            $stores = Mage::getModel('core/store')->getCollection();
        }
        foreach ($stores as $store) {
            // 404 pages
            $pageIds[] = Mage::getStoreConfig('web/default/cms_no_route', $store);
            // Enterprise Website Restrictions Pages
            if ($websiteRestrictions) {
                $pageIds[] = Mage::getStoreConfig('general/restriction/cms_page', $store);
            }
        }
        return array_unique($pageIds);
    }

    protected function _prepareCollection()
    {
        $stores = $this->getIndex()->getStore();
        $collection = Mage::getModel('cms/page')->getCollection();
        if ($stores != array(0)) {
            $collection->addStoreFilter($stores);
        }
        $collection->addFieldToFilter('identifier', array('nin' => $this->_getExcludedPages($stores)));
        $collection->addFieldToFilter('is_active', array('eq' => 1));
        return $collection;
    }

    protected function _getFirstStore($stores)
    {
        $firstStore = is_array($stores) && count($stores) ? $stores[0] : null;
        if ($firstStore == 0) {
            foreach (Mage::app()->getStores() as $store) {
                return $store->getId();
            }
        }
        return $firstStore;
    }

    protected function _fillDataFromCollection($collection, $checkRecordsExist = false)
    {
        $table = $this->getIndexTableModel();
        $addAttributes = $this->_getAttributes();
        $attCount = count($addAttributes);
        $stores = $this->getIndex()->getStore();
        $firstStore = $this->_getFirstStore($stores);
        foreach ($collection as $item) {
            $data = array(self::PRIMARY => $item->getData(self::PRIMARY));
            for ($j = 0; $j < $attCount; $j++) {
                $data[$addAttributes[$j]] = $item->getData($addAttributes[$j]);
            }
            if (isset($data['content'])) {
                $data['content'] = strip_tags($data['content']);
            }
            if ($checkRecordsExist) {
                $tableItem = $table->fetchRow($table->select()->where(self::PRIMARY . ' = ?', $item[self::PRIMARY]));
                if ($tableItem) {
                    $tableItem->setFromArray($data);
                    $tableItem->_updated = 1;
                    $tableItem->save();
                    continue;
                } else {
                    $data['_updated'] = 1;
                }
            }
            $table->insert($data);
        }
    }

    protected function _getPrimary()
    {
        return self::PRIMARY;
    }

    protected function _getPrimaryTable()
    {
        return '';
    }
}
