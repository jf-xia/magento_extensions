<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

$installer = $this;

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('customercredit_rules')};
CREATE TABLE {$this->getTable('customercredit_rules')} (
  `rule_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET cp1251 NOT NULL,
  `description` varchar(255) CHARACTER SET cp1251 NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `website_ids` varchar(255) CHARACTER SET cp1251 NOT NULL,
  `credit` decimal(10,0) NOT NULL,
  `customer_group_ids` varchar(255) CHARACTER SET cp1251 NOT NULL,
  `conditions_serialized` mediumtext CHARACTER SET cp1251,
  `actions_serialized` mediumtext CHARACTER SET cp1251,
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('customercredit_rules_customer')};
CREATE TABLE {$this->getTable('customercredit_rules_customer')} (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

");