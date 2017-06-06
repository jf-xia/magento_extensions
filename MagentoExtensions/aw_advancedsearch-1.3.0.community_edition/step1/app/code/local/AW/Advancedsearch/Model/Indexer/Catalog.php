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

class AW_Advancedsearch_Model_Indexer_Catalog extends AW_Advancedsearch_Model_Indexer_Abstract
{
    const PRIMARY = 'entity_id';

    protected $_indexTableModel = null;

    protected function _getIndexName()
    {
        return 'catalog_';
    }

    protected function _extendSqlAttributes(&$attributes)
    {
        foreach ($attributes as $k => $v) {
            if (!isset($v['extended'])) {
                $attributes[$k]['extended'] = 0;
            }
        }
        $attributes[] = array(
            'attribute' => '_updated',
            'type' => 'tinyint',
            'unsigned' => true,
            'is_null' => false,
            'default' => 0,
            'extra' => '');
        return $attributes;
    }

    protected function _getTextColumnDefinition()
    {
        return array(
            'unsigned' => null,
            'default' => null,
            'extra' => null,
            'type' => 'text',
            'is_null' => true
        );
    }

    protected function _createTable()
    {
        $pa = Mage::getResourceSingleton('catalog/product')->loadAllAttributes()->getAttributesByCode();
        $attributes = $this->getIndex()->getData('attributes');
        if ($attributes) {
            $attributes = array_merge(array(array('attribute' => self::PRIMARY)), $attributes);
        }
        $queryString = '';
        $this->_extendSqlAttributes($attributes);
        foreach ($attributes as $k => $attr) {
            $attrName = $attr['attribute'];
            if (isset($pa[$attrName])) {
                $flatColumn = $pa[$attrName]->getFlatColumns();
                if ($pa[$attrName]->getData('frontend_input')
                    && in_array($pa[$attrName]->getData('frontend_input'), array('select', 'multiselect'))
                ) {
                    $flatColumn[$attrName] = $this->_getTextColumnDefinition();
                }
                if (isset($flatColumn[$attrName])) {
                    $flatColumn = $flatColumn[$attrName];
                    $queryString .= "`{$attrName}` {$flatColumn['type']}";
                    if ($flatColumn['unsigned']) {
                        $queryString .= " unsigned";
                    }
                    $queryString .= " " . ($flatColumn['is_null'] ? 'NULL' : 'NOT NULL');
                    if ($flatColumn['default']) {
                        $queryString .= " DEFAULT " . $this->_getConnection()->quoteInto('?', $flatColumn['default']);
                    }
                    if ($flatColumn['extra']) {
                        $queryString .= ' ' . $flatColumn['extra'];
                    }
                    if ($attr != $attributes[count($attributes) - 1]) {
                        $queryString .= ",\n";
                    }
                }
            } else {
                if (isset($attr['type'])) {
                    $queryString .= "`{$attrName}` {$attr['type']}";
                    if ($attr['unsigned']) {
                        $queryString .= " unsigned";
                    }
                    $queryString .= " " . ($attr['is_null'] ? 'NULL' : 'NOT NULL');
                    if ($attr['default']) {
                        $queryString .= " DEFAULT " . $this->_getConnection()->quoteInto('?', $attr['default']);
                    }
                    if ($attr['extra']) {
                        $queryString .= ' ' . $attr['extra'];
                    }
                    if ($attr != $attributes[count($attributes) - 1]) {
                        $queryString .= ",\n";
                    }
                } else {
                    return Mage::helper('awadvancedsearch')->__('Can\'t use attribute "%s"', $attrName);
                }
            }
        }
        return parent::_createTable($queryString);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('awadvancedsearch/product_collection');
        $addAttributes = $this->_getAttributes();
        $collection->addAttributeToSelect($addAttributes);
        $_visibility = array(
            Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH
        );
        $collection->addAttributeToFilter('visibility', $_visibility)
            ->addAttributeToFilter('status', array("in" => Mage::getSingleton("catalog/product_status")->getVisibleStatusIds()));
        if (!$this->_getShowOutOfStock()) {
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
        }
        return $collection;
    }

    protected function _fillDataFromCollection($collection, $checkRecordsExist = false)
    {
        $table = $this->getIndexTableModel();
        $addAttributes = $this->_getAttributes();
        $attCount = count($addAttributes);
        $stores = $this->getIndex()->getStore();
        foreach ($collection as $item) {
            if (in_array(0, $stores) || array_intersect($item->getStoreIds(), $stores)) {
                $data = array(self::PRIMARY => $item->getData(self::PRIMARY));
                for ($j = 0; $j < $attCount; $j++) {
                    if (($attrText = $item->getResource()->getAttribute($addAttributes[$j])->getFrontend()->getValue($item))) {
                        if (is_array($attrText)) {
                            $attrText = implode(' ', $attrText);
                        }
                        $data[$addAttributes[$j]] = strip_tags($attrText);
                    } else {
                        $data[$addAttributes[$j]] = strip_tags($item->getData($addAttributes[$j]));
                    }
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
    }

    protected function _getShowOutOfStock()
    {
        $_show = true;
        if (($_ciHelper = Mage::helper('cataloginventory')) && method_exists($_ciHelper, 'isShowOutOfStock')) {
            $_show = $_ciHelper->isShowOutOfStock();
        }
        return $_show;
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
