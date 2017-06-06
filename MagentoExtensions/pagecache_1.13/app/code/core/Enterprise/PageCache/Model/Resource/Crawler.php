<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Enterprise
 * @package     Enterprise_PageCache
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */


/**
 * Crawler resource model
 *
 * @category    Enterprise
 * @package     Enterprise_PageCache
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Enterprise_PageCache_Model_Resource_Crawler extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Internal constructor
     */
    protected function _construct()
    {
        $this->_init('enterprise_urlrewrite/url_rewrite', 'url_rewrite_id');
    }

    /**
     * Get statement for iterating store urls
     *
     * @deprecated after 1.11.0.0 - use getUrlsPaths() instead
     *
     * @param int $storeId
     * @return Zend_Db_Statement
     */
    public function getUrlStmt($storeId)
    {
        $table = $this->getTable('core/url_rewrite');
        $select = $this->_getReadAdapter()->select()
            ->from($table, array('store_id', 'request_path'))
            ->where('store_id = :store_id')
            ->where('is_system=1');
        return $this->_getReadAdapter()->query($select, array(':store_id' => $storeId));
    }

    /**
     * Retrieve URLs paths that must be visited by crawler
     *
     * @param  $storeId
     * @return array
     * @deprecated after 1.12.0.2 - use getUrlsPaths() instead
     */
    public function getUrlsPaths($storeId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getTable('core/url_rewrite'), array('request_path'))
            ->where('store_id=?', $storeId)
            ->where('is_system=1');
        return $adapter->fetchCol($select);
    }

    /**
     * Retrieves request_path's that should be visited by crawler
     *
     * @param int $storeId
     * @return array
     */
    public function getRequestPaths($storeId)
    {
        $storeIdExpr = $this->_getReadAdapter()->getIfNullSql('url_category.store_id', 'url_product.store_id');

        $select = $this->_getReadAdapter()->select()
            ->from(array('url_rewrite' => $this->getTable('enterprise_urlrewrite/url_rewrite')),
                array('request_path', 'store_id' => $storeIdExpr))
            ->joinLeft(array('url_product' => $this->getTable('enterprise_urlrewrite/product')),
                'url_product.url_rewrite_id = url_rewrite.url_rewrite_id AND ' .
                $this->_getReadAdapter()->quoteInto('url_product.store_id = ?', $storeId),
                array()
            )
            ->joinLeft(array('url_category' => $this->getTable('enterprise_urlrewrite/category')),
                'url_category.url_rewrite_id = url_rewrite.url_rewrite_id AND ' .
                $this->_getReadAdapter()->quoteInto('url_category.store_id = ?', $storeId),
                array()
            )
            ->where('is_system = ?', 1);

        $result = $this->_getReadAdapter()->fetchAll($select);
        $requestPaths = array();
        foreach ($result as $item) {
            if ($storeId == $item['store_id']) {
                $requestPaths[] = $item['request_path'];
            }
        }
        return $requestPaths;
    }
}
