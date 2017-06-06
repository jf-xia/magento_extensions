<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    Mage_CMS
 * @copyright  Copyright (c) 2004-2007 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('blog/lblog')}
	ADD `cat_id` TINYINT( 11 ) NOT NULL AFTER `post_id` ;
");

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('blog/lcat')};
CREATE TABLE {$this->getTable('blog_cat')} (
`cat_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
`title` varchar( 255 ) NOT NULL default '',
`identifier` varchar( 255 ) NOT NULL default '',
`sort_order` tinyint ( 6 ) NOT NULL ,
`meta_keywords` text NOT NULL ,
`meta_description` text NOT NULL ,
PRIMARY KEY ( `cat_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO {$this->getTable('blog/lcat')} (
`cat_id` ,
`title`,
`identifier`
)
VALUES (
NULL , 'News', 'news'
);
");

$installer->endSetup();
