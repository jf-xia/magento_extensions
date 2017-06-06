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
 * @package    AW_Productquestions
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */
$installer = $this;

$installer->startSetup();

try
{
    $installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('productquestions/productquestions')};

CREATE TABLE {$this->getTable('productquestions/productquestions')} (
 `question_id` int(10) unsigned NOT NULL auto_increment,
 `question_status` tinyint(2) NOT NULL default '1',
 `question_product_id` int(10) unsigned NOT NULL default '0',
 `question_store_id` int(11) NOT NULL default '1' COMMENT 'asked from',
 `question_store_ids` varchar(255) NOT NULL default '0' COMMENT 'displayed on',
 `question_product_name` varchar(255) NOT NULL,
 `question_author_name` varchar(255) NOT NULL,
 `question_author_email` varchar(255) NOT NULL default '',
 `question_date` datetime NOT NULL default '0000-00-00 00:00:00',
 `question_text` text NOT NULL,
 `question_reply_text` text NOT NULL,
 PRIMARY KEY  (`question_id`),
 KEY `question_status` (`question_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- DROP TABLE IF EXISTS {$this->getTable('productquestions/helpfulness')};

CREATE TABLE {$this->getTable('productquestions/helpfulness')} (
 `question_id` int(10) unsigned NOT NULL default '0',
 `vote_count` int(10) unsigned NOT NULL default '0',
 `vote_sum` int(10) unsigned NOT NULL default '0',
 PRIMARY KEY  (`question_id`),
 CONSTRAINT `FK_helpfulness` FOREIGN KEY (`question_id`) REFERENCES `{$this->getTable('productquestions/productquestions')}` (`question_id`) ON DELETE CASCADE
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
}
catch (Exception $e) { Mage::logException($e); }

$installer->endSetup();

?>
