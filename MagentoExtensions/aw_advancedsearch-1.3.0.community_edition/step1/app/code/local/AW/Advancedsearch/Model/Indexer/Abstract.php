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

/** Compatibility with 1330 */
class AW_Advancedsearch_Model_Zend_Db_Table extends Zend_Db_Table
{
}

abstract class AW_Advancedsearch_Model_Indexer_Abstract
{
    const TABLE_PREFIX = 'aw_as_index_';

    protected $_index = null;
    protected $_connection = null;

    protected function _getLogHelper()
    {
        return Mage::helper('awadvancedsearch/log');
    }

    public function setIndex(AW_Advancedsearch_Model_Catalogindexes $index)
    {
        $this->_index = $index;
        return $this;
    }

    public function getIndex()
    {
        return $this->_index;
    }

    protected function _getConnection()
    {
        if ($this->_connection === null) {
            $this->_connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        }
        return $this->_connection;
    }

    protected function _getTableName()
    {
        $tablePrefix = (string)Mage::getConfig()->getTablePrefix();
        return $tablePrefix . self::TABLE_PREFIX . $this->_getIndexName() . $this->getIndex()->getId();
    }

    public function getTableName()
    {
        return $this->_getTableName();
    }

    protected function _removeTable()
    {
        $queryString = sprintf("DROP TABLE IF EXISTS %s", $this->_getConnection()->quoteIdentifier($this->_getTableName()));
        $this->_getConnection()->raw_query($queryString);
        $this->_getLogHelper()->log($this, 'Old table removed');
        return $this;
    }

    public function removeTable()
    {
        return $this->_removeTable();
    }

    protected function _isTableExists()
    {
        $tables = $this->_getConnection()->listTables();
        if (is_array($tables) && in_array($this->_getTableName(), $tables)) {
            return true;
        }
        return false;
    }

    protected function _getAttributes()
    {
        $addAttributes = array();
        foreach ($this->getIndex()->getData('attributes') as $attr) {
            $addAttributes[] = $attr['attribute'];
        }
        return $addAttributes;
    }

    protected function _getExtendedAttributes()
    {
        $addAttributes = array();
        foreach ($this->getIndex()->getData('attributes') as $attr) {
            $addAttributes[] = $attr['attribute'];
        }
        $this->_extendSqlAttributes($addAttributes);
        return $addAttributes;
    }

    public function reindex()
    {
        if ($this->getIndex() && $this->canIndex()) {
            $this->_getLogHelper()->log($this, 'Started building of catalog index table for index #' . $this->getIndex()->getId());
            $this->_removeTable();
            $_result = $this->_createTable();
            return $_result === true ? $this->_fillData() : $_result;
        }
        return false;
    }

    protected function _getColumnSql($name)
    {
        $column = $this->_getColumn($name);
        if ($column) {
            $queryString = '';
            $queryString .= "`{$name}` {$column['type']}";
            if ($column['unsigned']) {
                $queryString .= " unsigned";
            }
            $queryString .= " " . ($column['is_null'] ? 'NULL' : 'NOT NULL');
            if ($column['default']) {
                $queryString .= " DEFAULT " . $this->_getConnection()->quoteInto('?', $column['default']);
            }
            if ($column['extra']) {
                $queryString .= ' ' . $column['extra'];
            }
            return $queryString;
        }
        return null;
    }

    protected function _extendSqlAttributes(&$attributes)
    {
    }

    protected function _createTable()
    {
        $fieldsDefinition = func_get_arg(0);
        if ($fieldsDefinition) {
            $queryString = "CREATE TABLE " . $this->_getConnection()->quoteIdentifier($this->_getTableName()) . " (\n"
                . $fieldsDefinition
                . ")\n"
                . "ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $this->_getConnection()->raw_query($queryString);
            $this->_getLogHelper()->log($this, 'New table has been successfully created');
            return true;
        } else {
            $this->_getLogHelper()->log($this, 'Error of creating new table');
            return false;
        }
        return false;
    }

    protected function _fillData()
    {
        if ($this->_isTableExists()) {
            $this->_getLogHelper()->log($this, 'Started filling table with new data');
            try {
                $collection = $this->_prepareCollection();
                $pageSize = 100;
                $pagesCount = ceil($collection->getSize() / $pageSize);
                if (AW_Advancedsearch_Helper_Data::DEBUG_MODE) {
                    $this->_getLogHelper()->log($this, 'Products: ' . $collection->getSize() . ', pages: ' . $pagesCount);
                }
                for ($i = 1; $i <= $pagesCount; $i++) {
                    if (AW_Advancedsearch_Helper_Data::DEBUG_MODE) {
                        $this->_getLogHelper()->log($this, 'Processing page: ' . $i);
                    }
                    $collection = $this->_prepareCollection();
                    $collection->setPageSize($pageSize);
                    $collection->setCurPage($i);
                    $this->_fillDataFromCollection($collection);
                    unset($collection);
                }
                $this->_getLogHelper()->log($this, 'Done');
                return true;
            } catch (Exception $ex) {
                $this->_getLogHelper()->log($this, 'Error: ' . $ex->getMessage());
                return $ex->getMessage();
            }
        }
        return false;
    }

    public function canIndex()
    {
        return true;
    }

    public function resetUpdates()
    {
        $table = $this->getIndexTableModel();
        $data = array('_updated' => 0);
        $table->update($data, '1');
    }

    public function getIndexTableModel()
    {
        if ($this->_indexTableModel === null && $this->_isTableExists()) {
            $this->_indexTableModel = new AW_Advancedsearch_Model_Zend_Db_Table(array(
                Zend_Db_Table_Abstract::ADAPTER => $this->_getConnection(),
                Zend_Db_Table_Abstract::NAME => $this->_getTableName(),
                Zend_Db_Table_Abstract::PRIMARY => $this->_getPrimary()
            ));
        }
        return $this->_indexTableModel;
    }

    public function updateData($id)
    {
        if ($this->_isTableExists()) {
            $collection = $this->_prepareCollection();
            $field = ((strlen($this->_getPrimaryTable()) > 0)?$this->_getPrimaryTable() . '.':'') . $this->_getPrimary();
            $collection->addFieldToFilter($field, array('eq' => $id));
            if ($collection->getSize()) {
                // item updated
                $this->_fillDataFromCollection($collection, true);
            } else {
                // item disabled, or changed it`s visibility
            }
        }
    }

    abstract protected function _getIndexName();

    abstract protected function _getPrimary();

    abstract protected function _getPrimaryTable();

    abstract protected function _prepareCollection();

    abstract protected function _fillDataFromCollection($collection);
}
