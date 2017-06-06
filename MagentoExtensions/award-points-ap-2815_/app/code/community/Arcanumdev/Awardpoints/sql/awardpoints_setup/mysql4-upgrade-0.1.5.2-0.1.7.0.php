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
 $installer = $this; $installer->startSetup(); $installer->run(" CREATE TABLE IF NOT EXISTS {$this->getTable('awardpoints_rule')} ( `awardpoints_rule_id` INT( 11 ) NOT NULL AUTO_INCREMENT , `awardpoints_rule_name` VARCHAR( 255 ) NOT NULL , `awardpoints_rule_type` VARCHAR( 60 ) NOT NULL , `awardpoints_rule_test` TEXT NOT NULL , `awardpoints_rule_operator` VARCHAR( 50 ) NOT NULL , `awardpoints_rule_points` INT( 11 ) NOT NULL , `awardpoints_rule_extra` TINYINT( 1 ) NOT NULL , `website_ids` TEXT NULL , `awardpoints_rule_start` DATE NULL DEFAULT NULL , `awardpoints_rule_end` DATE NULL DEFAULT NULL , `awardpoints_rule_activated` TINYINT( 1 ) NOT NULL, PRIMARY KEY ( `awardpoints_rule_id` ) ) "); $installer->endSetup();