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


class AW_Advancedsearch_Model_Cron
{
    const LOCK = 'aw_as_cron_lock';
    const LOCKTIME_DEFAULT = 30; // 30 seconds

    public function runReindex()
    {
        if (self::checkLock()) {
            
            $this->_setLock();
       
            $this->_rebuildIndexes();
            
            /* restart Sphinx */
            $sphinxEngine = Mage::getModel('awadvancedsearch/engine_sphinx');
            $sphinxEngine->restartSearchd();
        }
    }
    
    public function checkDaemon()
    {
        if (Mage::helper('awadvancedsearch/config')->getGeneralEnabled()) {
            $sphinxIndexer = Mage::getModel('awadvancedsearch/engine_sphinx');
            if ($sphinxIndexer->checkSearchdState() === false) {
                $sphinxIndexer->startSearchd();
            }
        }
    }
    
    protected function _setLock()
    {
        Mage::app()->saveCache(time(), self::LOCK, array(), self::LOCKTIME_DEFAULT);
    }
    
    public static function checkLock()
    {
        $time = Mage::app()->loadCache(self::LOCK);

        if ($time && ((time() - $time) < self::LOCKTIME_DEFAULT)) {
            return false;
        }
        return true;
    }

    protected static function _getActiveIndexes()
    {
        $indexes = Mage::getModel('awadvancedsearch/catalogindexes')->getCollection();
        $indexes->addStatusFilter();
        return $indexes;
    }

    protected function _rebuildIndexes()
    {
        if(Mage::helper('awadvancedsearch/config')->getGeneralEnabled()) {
            self::_getLogHelper()->log(__CLASS__, 'Starting rebuilding indexes');
            foreach(self::_getActiveIndexes() as $index) {
                $index->setData('state', AW_Advancedsearch_Model_Source_Catalogindexes_State::REINDEX_REQUIRED)
                      ->save()
                      ->callAfterLoad();
                $indexer = $index->getIndexer();
                if($indexer) {
                    $result = $indexer->reindex();
                    if($result === true) {
                        $sphinxIndexer = Mage::getModel('awadvancedsearch/engine_sphinx');
                        $result = $sphinxIndexer->reindex($indexer);
                        if($result) {
                            $index->setData('state', AW_Advancedsearch_Model_Source_Catalogindexes_State::READY)
                                  ->save();
                        } else {
                            self::_getLogHelper()->log(__CLASS__, 'Some error occurs on rebuilding index');
                        }
                    } else if($result === false) {
                        self::_getLogHelper()->log(__CLASS__, 'Some error occurs on rebuilding index');
                    } else {
                        self::_getLogHelper()->log(__CLASS__, $result);
                    }
                } else {
                    self::_getLogHelper()->log(__CLASS__, 'Invalid indexer');
                }
            }
            self::_getLogHelper()->log(__CLASS__, 'Done');
        }
    }

    protected static function _getLogHelper()
    {
        return Mage::helper('awadvancedsearch/log');
    }
}
