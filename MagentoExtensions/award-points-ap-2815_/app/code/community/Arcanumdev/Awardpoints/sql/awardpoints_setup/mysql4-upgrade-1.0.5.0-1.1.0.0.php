<?php
 /*
 * Arcanum Dev AwardPoints
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to arcanumdev@wafunotamago.com so we can send you a copy immediately.
 *
 * @category   Magento Sale Extension
 * @package    AwardPoints
 * @copyright  Copyright (c) 2012 Arcanum Dev. Y.K. (http://arcanumdev.wafunotamago.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 $sqls = array(); $connection = Mage::getSingleton('core/resource') ->getConnection('awardpoints_read'); $select = $connection->select() ->from('information_schema.COLUMNS') ->where("COLUMN_NAME='date_start' AND TABLE_NAME='{$this->getTable('awardpoints_account')}'"); $data = $connection->fetchRow($select); if(!isset($data['COLUMN_NAME'])){ $sqls[] = "ALTER TABLE {$this->getTable('awardpoints_account')} ADD COLUMN `date_start` DATE NULL DEFAULT NULL AFTER `points_spent` , ADD `date_end` DATE NULL DEFAULT NULL AFTER `date_start`;"; } $select = $connection->select() ->from('information_schema.COLUMNS') ->where("COLUMN_NAME='store_id' AND TABLE_NAME='{$this->getTable('awardpoints_account')}'"); $data = $connection->fetchRow($select); if(!isset($data['COLUMN_NAME'])){ $sqls[] = "ALTER TABLE {$this->getTable('awardpoints_account')} DROP INDEX FK_catalog_category_ENTITY_STORE;"; $sqls[] = "ALTER TABLE {$this->getTable('awardpoints_account')} CHANGE `store_id` `store_id` TEXT NULL DEFAULT NULL;"; } if($sqls != array()){ $installer = $this; $installer->run(implode(' ',$sqls)); $installer->endSetup(); }