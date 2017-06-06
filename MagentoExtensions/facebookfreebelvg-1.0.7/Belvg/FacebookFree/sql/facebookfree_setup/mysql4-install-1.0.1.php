<?php

/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
  /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 * *************************************** */
/* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
  /***************************************
 *         DISCLAIMER   *
 * *************************************** */
/* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 * ****************************************************
 * @category   Belvg
 * @package    Belvg_FacebookAll
 * @copyright  Copyright (c) 2010 - 2011 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

$installer = $this;

$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('belvg_facebook_customer')} (
  `customer_id` int(10) NOT NULL,
  `fb_id` bigint(20) NOT NULL,
  UNIQUE KEY `FB_CUSTOMER` (`customer_id`,`fb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `{$this->getTable('belvg_facebook_customer')}`
CHANGE `customer_id` `customer_id` INT( 10 ) UNSIGNED NOT NULL;

ALTER TABLE `{$this->getTable('belvg_facebook_customer')}`
ADD FOREIGN KEY ( `customer_id` ) REFERENCES `{$this->getTable('customer_entity')}` (
`entity_id`
) ON DELETE CASCADE ;
");
$installer->endSetup();