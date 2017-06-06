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

class AW_Advancedsearch_Helper_Data extends Mage_Core_Helper_Abstract
{
    const VAR_DIR = 'aw_advancedsearch';
    const DEBUG_MODE = false;
    const INDEXES_REGISTRY_STORAGE = 'awas_indexes_storage';

    public function getVarDir($name = null)
    {
        $_varDir = Mage::getConfig()->getVarDir(self::VAR_DIR . ($name ? DS . $name : $name));
        if (!file_exists($_varDir))
            @mkdir($_varDir);
        return $_varDir;
    }

    public function isEnabled()
    {
        return !Mage::getStoreConfig('advanced/modules_disable_output/AW_Advancedsearch') && Mage::helper('awadvancedsearch/config')->getGeneralEnabled();
    }

    public function hasActiveIndexes()
    {
        $catalogIndexes = Mage::getModel('awadvancedsearch/catalogindexes')->getCollection();
        $catalogIndexes->addStatusFilter()
            ->addStoreFilter();
        return (bool)$catalogIndexes->getSize();
    }

    public function addIndexToDeltaReindex($index)
    {
        $indexes = Mage::registry(self::INDEXES_REGISTRY_STORAGE);
        if (!$indexes) {
            $indexes = array($index);
        } else {
            $indexes[] = $index;
            Mage::unregister(self::INDEXES_REGISTRY_STORAGE);
        }
        Mage::register(self::INDEXES_REGISTRY_STORAGE, $indexes);
    }

    public function getIndexesToDeltaReindex()
    {
        $indexes = Mage::registry(self::INDEXES_REGISTRY_STORAGE);
        return is_array($indexes) || $indexes instanceof Traversable ? $indexes : array();
    }

    public static function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($dir . DS . $object) == 'dir') {
                        self::rrmdir($dir . DS . $object);
                    } else {
                        unlink($dir . DS . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function isExtensionInstalled($name)
    {
        $modules = (array)Mage::getConfig()->getNode('modules')->children();
        return array_key_exists($name, $modules)
            && 'true' == (string)$modules[$name]->active
            && !(bool)Mage::getStoreConfig('advanced/modules_disable_output/' . $name);
    }


    public function checkExtensionVersion($extensionName, $extVersion, $operator = '>=')
    {
        if ($this->isExtensionInstalled($extensionName) && ($version = Mage::getConfig()->getModuleConfig($extensionName)->version)) {
            return version_compare($version, $extVersion, $operator);
        }
        return false;
    }

    public function canUseAWBlog()
    {
        return $this->checkExtensionVersion('AW_Blog', '1.1');
    }

    public function getBlogApiModel()
    {
        if ($this->canUseAWBlog()) {
            return Mage::getSingleton('blog/api');
        }
    }

    public function getKbaseArticleModel()
    {
        if ($this->canUseAWKBase()) {
            return Mage::getModel('kbase/article');
        }
    }

    public function canUseAWKBase()
    {
        return $this->checkExtensionVersion('AW_Kbase', '1.1');
    }

    public function isEditAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('awadvancedsearch/edit');
    }

    public function isViewAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('awadvancedsearch/manage_indexes');
    }
}
