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

class AW_Advancedsearch_Helper_Config extends Mage_Core_Helper_Abstract
{
    const GENERAL_ENABLE = 'awadvancedsearch/general/enable';

    const SPHINX_SERVER_PATH = 'awadvancedsearch/sphinx/server_path';
    const SPHINX_SERVER_ADDR = 'awadvancedsearch/sphinx/server_addr';
    const SPHINX_SERVER_PORT = 'awadvancedsearch/sphinx/server_port';
    const SPHINX_MATCH_MODE = 'awadvancedsearch/sphinx/match_mode';

    public function getGeneralEnabled($store = null)
    {
        return Mage::getStoreConfig(self::GENERAL_ENABLE, $store);
    }

    public static function getSphinxServerPath()
    {
         $path = Mage::getStoreConfig(self::SPHINX_SERVER_PATH);
         if ($path) {
            $path .= DS;
         }
         return $path;
    }


    public static function getSphinxServerAddr()
    {
        return Mage::getStoreConfig(self::SPHINX_SERVER_ADDR);
    }

    public static function getSphinxServerPort()
    {
        return Mage::getStoreConfig(self::SPHINX_SERVER_PORT);
    }

    public static function getSphinxMatchMode()
    {
        return Mage::getStoreConfig(self::SPHINX_MATCH_MODE);
    }

    public function getSphinxConfig()
    {
        return array(
            'addr' => self::getSphinxServerAddr(),
            'port' => self::getSphinxServerPort(),
        );
    }
}
