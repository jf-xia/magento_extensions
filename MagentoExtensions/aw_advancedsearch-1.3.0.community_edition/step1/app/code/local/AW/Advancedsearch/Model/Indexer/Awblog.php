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

class AW_Advancedsearch_Model_Indexer_Awblog extends AW_Advancedsearch_Model_Indexer_Abstract
{
    const PRIMARY = 'post_id';

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
        'short_content' => array(
            'type' => 'text',
            'unsigned' => null,
            'is_null' => false,
            'default' => null,
            'extra' => null
        ),
        'post_content' => array(
            'type' => 'text',
            'unsigned' => null,
            'is_null' => false,
            'default' => null,
            'extra' => null
        ),
        'tags' => array(
            'type' => 'text',
            'unsigned' => null,
            'is_null' => false,
            'default' => null,
            'extra' => null
        ),
        'category' => array(
            'type' => 'text',
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
        return 'awblog_';
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

    protected function _prepareCollection()
    {
        $blogAPIModel = Mage::helper('awadvancedsearch')->getBlogAPIModel();
        $stores = $this->getIndex()->getStore();
        $collection = $blogAPIModel->getPosts(array(AW_Blog_Model_Status::STATUS_ENABLED), $stores == array(0) ? array() : $stores);
        return $collection;
    }

    public function canIndex()
    {
        return Mage::helper('awadvancedsearch')->canUseAWBlog();
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
        $blogAPIModel = Mage::helper('awadvancedsearch')->getBlogAPIModel();
        $table = $this->getIndexTableModel();
        $addAttributes = $this->_getAttributes();
        $attCount = count($addAttributes);
        $stores = $this->getIndex()->getStore();
        $firstStore = $this->_getFirstStore($stores);
        foreach ($collection as $item) {
            $data = array(self::PRIMARY => $item->getData(self::PRIMARY));
            for ($j = 0; $j < $attCount; $j++) {
                switch ($addAttributes[$j]) {
                    case 'short_content':
                        $data['short_content'] = strip_tags($blogAPIModel->getPostShortContent($item, $firstStore));
                        break;
                    case 'post_content':
                        $data['post_content'] = strip_tags($item->getPostContent());
                        break;
                    case 'category':
                        $categories = array();
                        foreach ($blogAPIModel->getPostCategories($item->getId()) as $category) {
                            $categories[] = $category->getData('title');
                        }
                        $data['category'] = implode(',', $categories);
                        break;
                    default:
                        $data[$addAttributes[$j]] = strip_tags($item->getData($addAttributes[$j]));
                        break;
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

    protected function _getPrimary()
    {
        return self::PRIMARY;
    }

    protected function _getPrimaryTable()
    {
        return '';
    }
}
