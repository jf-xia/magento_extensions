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

class AW_Advancedsearch_Model_Catalogindexes extends Mage_Core_Model_Abstract
{
    const TMP_TABLE_PREFIX = 'aw_as_tt_';

    public function _construct()
    {
        $this->_init('awadvancedsearch/catalogindexes');
    }

    /**
     * Unserialize database fields
     * @return AW_Advancedsearch_Model_Catalogindexes
     */
    protected function _afterLoad()
    {
        if ($this->getData('attributes') && is_string($this->getData('attributes')))
            $this->setData('attributes', @unserialize($this->getData('attributes')));
        if ($this->getData('store') !== null && !is_array($this->getData('store')))
            $this->setData('store', @explode(',', $this->getData('store')));
        return parent::_afterLoad();
    }

    /**
     * Serialize fields for database storage
     * @return AW_Advancedsearch_Model_Catalogindexes
     */
    protected function _beforeSave()
    {
        if ($this->getData('attributes') && is_array($this->getData('attributes')))
            $this->setData('attributes', @serialize($this->getData('attributes')));
        if ($this->getData('store') !== null && is_array($this->getData('store')))
            $this->setData('store', @implode(',', $this->getData('store')) ?
                @implode(',', $this->getData('store')) : 0);
        return parent::_beforeSave();
    }

    public function callAfterLoad()
    {
        return $this->_afterLoad();
    }

    public function getIndexer()
    {
        switch ($this->getData('type')) {
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::CATALOG:
                return Mage::getModel('awadvancedsearch/indexer_catalog')->setIndex($this);
                break;
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::CMS_PAGES:
                return Mage::getModel('awadvancedsearch/indexer_cms_pages')->setIndex($this);
                break;
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_BLOG:
                return Mage::getModel('awadvancedsearch/indexer_awblog')->setIndex($this);
                break;
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_KBASE:
                return Mage::getModel('awadvancedsearch/indexer_awkbase')->setIndex($this);
                break;
        }
        return null;
    }

    public function getIndexName()
    {
        $key = (string)Mage::getConfig()->getNode('global/crypt/key');
        return 'awasi' . $key . $this->getId();
    }

    public function setLastUpdate($time = null)
    {
        $date = date(AW_Advancedsearch_Model_Mysql4_Catalogindexes::MYSQL_DATETIME_FORMAT, $time ? $time : time());
        $this->setData('last_update', $date);
        $this->save();
        return $this;
    }

    public function getResultsCount()
    {
        $results = $this->getResults();
        if ($results) {
            return $results->getSize();
        }
        return null;
    }

    public function getResults()
    {
        if (!$this->getData('_results')) {
            $collection = null;
            switch ($this->getData('type')) {
                case AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_BLOG:
                    $matchedIds = Mage::helper('awadvancedsearch/results')->getMatchedIds($this);
                    $blogAPIModel = $this->getBlogAPI();
                    $collection = $blogAPIModel->getPosts(array(AW_Blog_Model_Status::STATUS_ENABLED), array(Mage::app()->getStore()->getId()));
                    $collection->addFieldToFilter('main_table.post_id', array('in' => $matchedIds));
                    $this->_joinRelevance($collection, $matchedIds, 'main_table.post_id');
                    $collection->getSelect()->order('relevance');
                    break;
                case AW_Advancedsearch_Model_Source_Catalogindexes_Types::CMS_PAGES:
                    $matchedIds = Mage::helper('awadvancedsearch/results')->getMatchedIds($this);
                    $collection = Mage::getModel('cms/page')->getCollection();
                    $collection->addFieldToFilter(AW_Advancedsearch_Model_Indexer_Cms_Pages::PRIMARY, array('in' => $matchedIds));
                    $collection->addFieldToFilter('is_active', 1);
                    $collection->addStoreFilter(Mage::app()->getStore()->getId());
                    break;
                case AW_Advancedsearch_Model_Source_Catalogindexes_Types::CATALOG:
                    $matchedIds = Mage::helper('awadvancedsearch/results')->getMatchedIds($this);
                    $collection = Mage::getModel('awadvancedsearch/product_collection');
                    if ($matchedIds) {
                        $collection->addAttributeToFilter('entity_id', array('in' => $matchedIds));
                        $this->_joinRelevance($collection, $matchedIds);
                    } else {
                        $collection->addAttributeToFilter('entity_id', array('in' => -1));
                    }
                    break;
                case AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_KBASE:
                    if (!Mage::helper('awadvancedsearch')->canUseAWKBase()) {
                        break;
                    }
                    $matchedIds = Mage::helper('awadvancedsearch/results')->getMatchedIds($this);
                    $kBaseCollection = Mage::helper('awadvancedsearch')->getKbaseArticleModel();
                    $collection = $kBaseCollection->getCollection();
                    $collection->addStoreFilter(Mage::app()->getStore()->getId());
                    $collection->addFieldToFilter('main_table.article_id', array('in' => $matchedIds));
                    $this->_joinRelevance($collection, $matchedIds, 'main_table.article_id');
                    $collection->getSelect()->order('relevance')->distinct();
                    break;
            }
            if ($collection) {
                $this->setData('_results', $collection);
            }
        }
        return $this->getData('_results');
    }

    public function getBlogAPI()
    {
        if ($this->_blogAPIModel === null) {
            $this->_blogAPIModel = Mage::helper('awadvancedsearch')->getBlogAPIModel();
        }
        return $this->_blogAPIModel;
    }

    /**
     * Joins relevance to the collection
     * item with relevance = 1 -- the most relevant
     * @param $collection
     * @param $ids
     * @param string $mainTableKeyField
     */
    protected function _joinRelevance($collection, $ids, $mainTableKeyField = 'e.entity_id')
    {
        $this->_createTempTable($ids);
        $collection->getSelect()->joinLeft(
            array('tmp_table' => $this->_getTempTableName()),
            '(tmp_table.entity_id=' . $mainTableKeyField . ')',
            array('relevance' => 'tmp_table.id')
        );
        $collection->getSelect()->where('tmp_table.id IS NOT NULL');
    }

    protected function _getTempTableName()
    {
        return self::TMP_TABLE_PREFIX . $this->getId();
    }

    protected function _createTempTable($ids)
    {
        if (is_null($ids)) {
            $ids = array();
        }
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $connection->raw_query(
            "CREATE TEMPORARY TABLE `" . $this->_getTempTableName() . "` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `entity_id` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
            INSERT INTO `" . $this->_getTempTableName() . "` (`entity_id`) VALUES (" . implode('), (', $ids) . ");"
        );
    }
}
