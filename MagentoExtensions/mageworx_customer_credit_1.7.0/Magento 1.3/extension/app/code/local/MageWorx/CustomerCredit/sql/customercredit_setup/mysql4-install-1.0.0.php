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
/* @var $installer Mage_Sales_Model_Entity_Setup */
$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('customercredit_credit')};
CREATE TABLE {$this->getTable('customercredit_credit')} (
  `credit_id` int(10) unsigned NOT NULL auto_increment,
  `customer_id` int(10) unsigned NOT NULL,
  `website_id` smallint(5) unsigned NOT NULL,
  `value` decimal(12,4) NOT NULL,
  PRIMARY KEY (`credit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('customercredit_credit_log')};
CREATE TABLE {$this->getTable('customercredit_credit_log')} (
  `log_id` int(10) unsigned NOT NULL auto_increment,
  `credit_id` int(10) unsigned NOT NULL,
  `action_type` TINYINT( 1 ) UNSIGNED NOT NULL,
  `action_date` DATETIME default NULL,
  `value` decimal(12,4) NOT NULL,
  `value_change` decimal(12,4) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('customercredit_code')};
CREATE TABLE {$this->getTable('customercredit_code')} (
  `code_id` int(10) unsigned NOT NULL auto_increment,
  `website_id` smallint(5) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `credit` decimal(12,4) NOT NULL,
  `created_date` DATETIME default NULL,
  `updated_date` DATETIME default NULL,
  `used_date` DATE default NULL,
  `from_date` DATE default NULL,
  `to_date` DATE default NULL,
  `is_active` TINYINT(1) UNSIGNED NOT NULL,
  PRIMARY KEY (`code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('customercredit_code_log')};
CREATE TABLE {$this->getTable('customercredit_code_log')} (
  `log_id` int(10) unsigned NOT NULL auto_increment,
  `code_id` int(10) unsigned NOT NULL,
  `action_type` TINYINT(1) UNSIGNED NOT NULL,
  `action_date` DATETIME default NULL,
  `credit` decimal(12,4) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->addAttribute('quote', 'customer_credit_total', array('type'=>'decimal'));
$installer->addAttribute('quote', 'base_customer_credit_total', array('type'=>'decimal'));

$installer->addAttribute('quote_address', 'customer_credit_amount', array('type'=>'decimal'));
$installer->addAttribute('quote_address', 'base_customer_credit_amount', array('type'=>'decimal'));

$installer->addAttribute('order', 'customer_credit_amount', array('type'=>'decimal'));
$installer->addAttribute('order', 'base_customer_credit_amount', array('type'=>'decimal'));

$installer->addAttribute('order', 'customer_credit_invoiced', array('type'=>'decimal'));
$installer->addAttribute('order', 'base_customer_credit_invoiced', array('type'=>'decimal'));

$installer->addAttribute('order', 'customer_credit_refunded', array('type'=>'decimal'));
$installer->addAttribute('order', 'base_customer_credit_refunded', array('type'=>'decimal'));

$installer->addAttribute('invoice', 'customer_credit_amount', array('type'=>'decimal'));
$installer->addAttribute('invoice', 'base_customer_credit_amount', array('type'=>'decimal'));

$installer->addAttribute('creditmemo', 'customer_credit_amount', array('type'=>'decimal'));
$installer->addAttribute('creditmemo', 'base_customer_credit_amount', array('type'=>'decimal'));

$installer->endSetup();