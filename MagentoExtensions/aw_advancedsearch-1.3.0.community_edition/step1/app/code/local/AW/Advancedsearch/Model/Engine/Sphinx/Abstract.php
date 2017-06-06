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

class AW_Advancedsearch_Model_Engine_Sphinx_Abstract
{
    protected $_indexer = null;

    protected $_sphinx = null;

    public function getSphinxEngine()
    {
        if ($this->_sphinx === null) {
            $this->_sphinx = Mage::getModel('awadvancedsearch/engine_sphinx');
        }
        return $this->_sphinx;
    }

    protected function _getHelper($name = null)
    {
        return Mage::helper('awadvancedsearch' . ($name ? '/' . $name : ''));
    }

    protected function _getLogHelper()
    {
        return $this->_getHelper('log');
    }

    public function setIndexer($indexer)
    {
        $this->_indexer = $indexer;
        return $this;
    }

    public function getIndexer()
    {
        return $this->_indexer;
    }

    protected function _createFullIndexConfig()
    {
        if (!$this->_isConfigCreated()) {
            return $this->getSphinxEngine()->createConfigFile();
        }
        return true;
    }

    protected function _getIndexName()
    {
        return $this->getIndexer()->getIndex()->getIndexName();
    }

    protected function _getDeltaIndexName()
    {
        return $this->_getIndexName() . 'delta';
    }

    public function removeVarDir($indexName = null)
    {
        if ($this->getSelfVarDir()) {
            if ($indexName) {
                foreach (glob($this->getSelfVarDir() . DS . $indexName . '.*') as $file) {
                    @unlink($file);
                }
            } else {
                Mage::helper('awadvancedsearch')->rrmdir($this->getSelfVarDir());
            }
        }
        return $this;
    }

    public function getSelfVarDir()
    {
        $_varDir = null;
        if ($this->getIndexer() && $this->getIndexer()->getIndex() && $this->getIndexer()->getIndex()->getId()) {
            $_varDir = $this->_getHelper()->getVarDir($this->getIndexer()->getIndex()->getId());
        }
        return $_varDir;
    }

    public function reindex($indexer = null, $delta = false)
    {
        if ($indexer) {
            $this->setIndexer($indexer);
        } else {
            $indexer = $this->getIndexer();
        }
        if ($indexer) {
            if ($this->_createFullIndexConfig()) {
                $indexName = $delta ? $this->_getDeltaIndexName() : $this->_getIndexName();
                $this->removeVarDir($indexName);
                $this->getSelfVarDir();
                $this->_getLogHelper()->log($this, 'Sphinx: Executing indexer for ' . $indexName);
                $path = Mage::helper('awadvancedsearch/config')->getSphinxServerPath();
                ob_start();
                passthru($path . AW_Advancedsearch_Model_Engine_Sphinx::INDEXER_CALL . ' -c ' . $this->getSphinxEngine()->getConfigFileName() . ' ' . $indexName, $_ret);
                $_out = ob_get_contents();
                ob_end_clean();
                if ($_ret === 0) {
                    $indexer->getIndex()->setLastUpdate();
                } else {
                    $this->_getLogHelper()->log($this, 'Sphinx error', null, $_out);
                }
                $this->_getLogHelper()->log($this, 'Sphinx: Done, returned value ' . $_ret);
                return $_ret === 0;
            }
        }
        return false;
    }

    public function reindexDelta($indexer = null)
    {
        return $this->reindex($indexer, true);
    }

    protected function _isConfigCreated()
    {
        if (file_exists($this->getSphinxEngine()->getConfigFileName())) {
            $config = @file_get_contents($this->getSphinxEngine()->getConfigFileName());
            if ($config) {
                return (bool)preg_match("/{$this->getIndexer()->getIndex()->getIndexName()}/mi", $config);
            }
        }
        return false;
    }

    public function mergeDeltaWithMain($indexer = null)
    {
        if ($indexer) {
            $this->setIndexer($indexer);
        } else {
            $indexer = $this->getIndexer();
        }
        if ($indexer) {
            $mainIndex = $this->_getIndexName();
            $deltaIndex = $this->_getDeltaIndexName();
            $this->_getLogHelper()->log($this, 'Sphinx: merging ' . $deltaIndex . ' with ' . $mainIndex);
            ob_start();
            passthru(AW_Advancedsearch_Model_Engine_Sphinx::INDEXER_CALL . ' -c ' . $this->getSphinxEngine()->getConfigFileName() . ' --merge ' . $mainIndex . ' ' . $deltaIndex . ' --rotate', $_ret);
            $_out = ob_get_contents();
            ob_end_clean();
            if ($_ret === 0) {
                $indexer->getIndex()->setLastUpdate();
            } else {
                $this->_getLogHelper()->log($this, 'Sphinx error', null, $_out);
            }
            $this->_getLogHelper()->log($this, 'Sphinx: Done, returned value ' . $_ret);
            return $_ret === 0;
        }
        return false;
    }

    public function getConfigFileContent()
    {
        $indexName = $this->_getIndexName();
        $deltaIndexName = $this->_getDeltaIndexName();
        $sphinxConfig = Mage::getStoreConfig('awadvancedsearch/sphinx');
        $_files = array('index_path' => $this->getSelfVarDir() . DS . $indexName,
            'delta_index_path' => $this->getSelfVarDir() . DS . $deltaIndexName);
        $fcontent = <<<FILE
source {$indexName} : dbconnect
{
    sql_query_pre = SET NAMES utf8
    sql_query = SELECT * FROM {$this->getIndexer()->getTableName()}
    sql_attr_uint = _updated
    sql_ranged_throttle = 0
}
source {$deltaIndexName} : {$indexName}
{
    sql_query = SELECT * FROM {$this->getIndexer()->getTableName()} WHERE `_updated` = 1
}
index {$indexName}
{
    source = {$indexName}
    path = {$_files['index_path']}
    docinfo = extern
    mlock = 0
    morphology = stem_enru
    min_word_len = 2
    charset_type = utf-8
    charset_table = 0..9, A..Z->a..z, _, a..z, U+410..U+42F->U+430..U+44F, U+430..U+44F
    min_infix_len = 2
    enable_star = 1
}
index {$deltaIndexName} : {$indexName}
{
    source = {$deltaIndexName}
    path = {$_files['delta_index_path']}
}

FILE;
        return $fcontent;
    }
}
