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
 $installer = $this; $installer->startSetup(); $installer->run(" DROP TABLE IF EXISTS {$this->getTable('awardpoints/pointrules')}; CREATE TABLE IF NOT EXISTS {$this->getTable('awardpoints/pointrules')} ( `rule_id` int(11) unsigned NOT NULL auto_increment, `title` varchar(166) NOT NULL, `status` int(11) NOT NULL, `website_ids` varchar( 255 ) NULL, `customer_group_ids` varchar( 255 ) NULL, `action_type` int(11) NULL, `conditions_serialized` mediumtext NOT NULL, `from_date` date DEFAULT NULL, `to_date` date DEFAULT NULL, `sort_order` int(10) unsigned NOT NULL DEFAULT '0', `points` int(11) NOT NULL, `rule_type` TINYINT( 2 ) NULL DEFAULT NULL, PRIMARY KEY(`rule_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;  DROP TABLE IF EXISTS {$this->getTable('awardpoints/catalogpointrules')}; CREATE TABLE IF NOT EXISTS {$this->getTable('awardpoints/catalogpointrules')} ( `rule_id` int(11) unsigned NOT NULL auto_increment, `title` varchar(166) NOT NULL, `status` int(11) NOT NULL, `website_ids` varchar( 255 ) NULL, `customer_group_ids` varchar( 255 ) NULL, `action_type` int(11) NULL, `conditions_serialized` mediumtext NOT NULL, `from_date` date DEFAULT NULL, `to_date` date DEFAULT NULL, `sort_order` int(10) unsigned NOT NULL DEFAULT '0', `points` int(11) NOT NULL, `rule_type` TINYINT( 2 ) NULL DEFAULT NULL, PRIMARY KEY(`rule_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;  ");  $installer->endSetup();