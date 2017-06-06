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
 $installer = $this; $installer->startSetup(); $sqlAdd = ''; $connection = Mage::getSingleton('core/resource') ->getConnection('awardpoints_read'); $select = $connection->select() ->from('information_schema.COLUMNS') ->where("COLUMN_NAME='awardpoints_referral_id' AND TABLE_NAME='{$this->getTable('awardpoints_account')}'"); $data = $connection->fetchRow($select); if(!isset($data['COLUMN_NAME'])){ $sql_add = "ALTER TABLE {$this->getTable('awardpoints_account')} ADD COLUMN `awardpoints_referral_id` INT( 11 ) NULL AFTER `points_spent`;"; } $installer->run("$sqlAdd  -- DROP TABLE IF EXISTS {$this->getTable('awardpoints_referral')}; CREATE TABLE IF NOT EXISTS {$this->getTable('awardpoints_referral')} ( `awardpoints_referral_id` INTEGER(11) UNSIGNED NOT NULL AUTO_INCREMENT, `awardpoints_referral_parent_id` INTEGER(11) UNSIGNED NOT NULL, `awardpoints_referral_child_id` INTEGER(11) UNSIGNED DEFAULT NULL, `awardpoints_referral_email` VARCHAR(255) NOT NULL DEFAULT '', `awardpoints_referral_status` TINYINT(1) DEFAULT '0',  PRIMARY KEY (`awardpoints_referral_id`), UNIQUE KEY `email` (`awardpoints_referral_email`), UNIQUE KEY `son_id` (`awardpoints_referral_child_id`), KEY `FK_customer_entity` (`awardpoints_referral_parent_id`), CONSTRAINT `awardpoints_referral_parent_fk` FOREIGN KEY (`awardpoints_referral_parent_id`) REFERENCES {$this->getTable('customer_entity')} (`entity_id`), CONSTRAINT `awardpoints_referral_child_fk1` FOREIGN KEY (`awardpoints_referral_child_id`) REFERENCES {$this->getTable('customer_entity')} (`entity_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8; ");  $installer->endSetup(); 