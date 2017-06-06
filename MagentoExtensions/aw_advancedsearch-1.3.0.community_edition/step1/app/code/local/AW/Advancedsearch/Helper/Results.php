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

class AW_Advancedsearch_Helper_Results extends Mage_Core_Helper_Abstract
{
    protected $_sphinxConnection = null;
    protected $_blogAPIModel = null;

    public function getSphinxConnection()
    {
        if ($this->_sphinxConnection === null) {
            $sphinxClient = Mage::getModel('awadvancedsearch/engine_sphinx')->connect();
            if ($sphinxClient instanceof SphinxClient) {
                $sphinxClient->SetMatchMode(Mage::helper('awadvancedsearch/config')->getSphinxMatchMode());
                $sphinxClient->SetSortMode(SPH_SORT_RELEVANCE);
                $sphinxClient->SetLimits(0, 1000, 1000);
                $this->_sphinxConnection = $sphinxClient;
            }
        }
        return $this->_sphinxConnection;
    }

    public function getIndexes($store = null, $type = null)
    {
        $indexes = Mage::getModel('awadvancedsearch/catalogindexes')->getCollection();
        $indexes->addStatusFilter()
            ->addStateFilter()
            ->addStoreFilter($store)
            ->setTypeOrder();
        if ($type) {
            $indexes->addTypeFilter($type);
        }
        return $indexes;
    }

    public function query($queryText, $store = null, $indexType = null)
    {
        $catalogSearchHelper = Mage::helper('catalogsearch');
        $sphinxConnection = $this->getSphinxConnection();
        
        $queryText=$sphinxConnection->EscapeString($queryText);

        if (strlen($queryText) >= $catalogSearchHelper->getMinQueryLength() && $sphinxConnection) {
            $indexes = $this->getIndexes($store, $indexType);
            if ($indexes->getSize()) {
                foreach ($indexes as $index) {
                    $attributes = $index->getData('attributes');
                    $attributeWeights = array();
                    foreach ($attributes as $attribute) {
                        $attributeWeights[$attribute['attribute']] = (int)$attribute['weight'];
                    }
                    $sphinxConnection->SetFieldWeights($attributeWeights);
                    $sphinxConnection->AddQuery($queryText, $index->getIndexName());
                }
                $results = $sphinxConnection->RunQueries();
                if ($results) {
                    $i = 0;
                    foreach ($indexes as $index) {
                        $index->setData('sphinx_results', $results[$i++]);
                    }
                    return $indexes;
                }
            }
        }
        return false;
    }

    public function getMatchesCount($index)
    {
        if ($index->getData('sphinx_results') && is_array($index->getData('sphinx_results'))) {
            $sphinxResult = $index->getData('sphinx_results');
            return isset($sphinxResult['total']) ? $sphinxResult['total'] : null;
        }
        return null;
    }

    public function getMatchedIds($index)
    {
        if ($index->getData('sphinx_results') && is_array($index->getData('sphinx_results'))) {
            $sphinxResult = $index->getData('sphinx_results');
            if (isset($sphinxResult['matches'])) {
                return array_keys($sphinxResult['matches']);
            }
        }
    }

    public function getBlogAPI()
    {
        if ($this->_blogAPIModel === null) {
            $this->_blogAPIModel = Mage::helper('awadvancedsearch')->getBlogAPIModel();
        }
        return $this->_blogAPIModel;
    }
}
