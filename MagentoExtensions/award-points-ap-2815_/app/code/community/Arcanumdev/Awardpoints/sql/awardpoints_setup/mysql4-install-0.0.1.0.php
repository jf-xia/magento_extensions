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
 $installer = $this; $installer->startSetup(); $installer->run(" -- DROP TABLE IF EXISTS {$this->getTable('awardpoints_account')}; CREATE TABLE IF NOT EXISTS {$this->getTable('awardpoints_account')} ( awardpoints_account_id integer(10) unsigned NOT NULL auto_increment, customer_id integer(10) unsigned NOT NULL default '0', store_id TEXT NULL DEFAULT NULL, order_id varchar(60) NOT NULL default '0', points_current integer(10) unsigned NULL default '0', points_spent integer(10) unsigned NULL default '0', `date_start` DATE NULL default NULL, `date_end` DATE NULL default NULL, awardpoints_referral_id int(11) DEFAULT NULL, PRIMARY KEY (awardpoints_account_id), KEY FK_sales_order_ENTITY_STORE (order_id), KEY customer_id (customer_id) ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Award points for an account';  -- DROP TABLE IF EXISTS {$this->getTable('awardpoints_referral')}; CREATE TABLE IF NOT EXISTS {$this->getTable('awardpoints_referral')} ( `awardpoints_referral_id` INTEGER(11) UNSIGNED NOT NULL AUTO_INCREMENT, `awardpoints_referral_parent_id` INTEGER(11) UNSIGNED NOT NULL, `awardpoints_referral_child_id` INTEGER(11) UNSIGNED DEFAULT NULL, `awardpoints_referral_email` VARCHAR(255) NOT NULL DEFAULT '', `awardpoints_referral_name` VARCHAR(255) NULL, `awardpoints_referral_status` TINYINT(1) DEFAULT '0', PRIMARY KEY (`awardpoints_referral_id`), UNIQUE KEY `email` (`awardpoints_referral_email`), UNIQUE KEY `son_id` (`awardpoints_referral_child_id`), KEY `FK_customer_entity` (`awardpoints_referral_parent_id`), CONSTRAINT `awardpoints_referral_parent_fk` FOREIGN KEY (`awardpoints_referral_parent_id`) REFERENCES {$this->getTable('customer_entity')} (`entity_id`), CONSTRAINT `awardpoints_referral_child_fk1` FOREIGN KEY (`awardpoints_referral_child_id`) REFERENCES {$this->getTable('customer_entity')} (`entity_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;  CREATE TABLE IF NOT EXISTS {$this->getTable('awardpoints_rule')} ( `awardpoints_rule_id` INT( 11 ) NOT NULL AUTO_INCREMENT , `awardpoints_rule_name` VARCHAR( 255 ) NOT NULL , `awardpoints_rule_type` VARCHAR( 60 ) NOT NULL , `awardpoints_rule_test` TEXT NOT NULL , `awardpoints_rule_operator` VARCHAR( 50 ) NOT NULL , `awardpoints_rule_points` INT( 11 ) NOT NULL , `awardpoints_rule_extra` TINYINT( 1 ) NOT NULL , `website_ids` TEXT NULL , `awardpoints_rule_start` DATE NULL DEFAULT NULL , `awardpoints_rule_end` DATE NULL DEFAULT NULL , `awardpoints_rule_activated` TINYINT( 1 ) NOT NULL, PRIMARY KEY ( `awardpoints_rule_id` ) ) ");  $installer->endSetup();