<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
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
 * @package    AW_Relatedproducts
 * @version    1.4.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */


class AW_Relatedproducts_Test_Model_Mocks_Foreignresetter extends Mage_Core_Model_Abstract {

    public static $counter = 0;

    public static function dropForeignKeys() {

        if (!self::$counter) {

            $resource = Mage::getModel('core/resource');
            $connection = $resource->getConnection('core_write');


            $FKscope = array(
                'aw_relatedproducts' => array('FK_WBTAB_INT_STORE_ID')
            );

            foreach ($FKscope as $table => $fks) {
                foreach ($fks as $fk) {
                    try {
                        $connection->exec(new Zend_Db_Expr("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fk}`"));
                        $connection->exec(new Zend_Db_Expr("ALTER TABLE `{$table}` DROP KEY `{$fk}`"));
                    } catch (Exception $e) {
                        
                    }
                }
            }


            self::$counter = 1;
        }
    }

}