<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Guestbook
 * @version    1.1
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

$installer = $this;
$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('mp_gb_messages')};

CREATE TABLE IF NOT EXISTS {$this->getTable('mp_gb_messages')}(
  `message_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `store_id` SMALLINT(5) UNSIGNED NOT NULL,
  `reply_to` BIGINT UNSIGNED,
  `customer_id` INT(10) UNSIGNED,
  `status` smallint(5) UNSIGNED NOT NULL,
  `message` TEXT,
  `name` VARCHAR(255),
  `subject` VARCHAR(255),
  `email` VARCHAR(255),
  `session_id` VARCHAR(255),
  `created_at` timestamp NOT NULL ,
  `updated_at` timestamp NOT NULL ,
  PRIMARY KEY (`message_id`),
  INDEX (`session_id`),
  INDEX `KEY_MPGUESTBOOK_COMMENTS_COMMENTS` (`reply_to`),
  CONSTRAINT `KEY_MPGUESTBOOK_COMMENTS_COMMENTS` FOREIGN KEY (`reply_to`) REFERENCES `{$this->getTable('mp_gb_messages')}` (`message_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB CHARSET=utf8;

    ");

$installer->endSetup();