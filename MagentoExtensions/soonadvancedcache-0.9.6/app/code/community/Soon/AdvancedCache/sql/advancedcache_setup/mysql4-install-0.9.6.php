<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G. ()
 */
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('advancedcache_config')};
CREATE TABLE {$this->getTable('advancedcache_config')} (
  `id` tinyint(4) unsigned NOT NULL auto_increment,
  `tag` text NOT NULL default '',
  `value` int(10) NOT NULL default '7200',
  `label` text NOT NULL default '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO advancedcache_config (`id`,`tag`,`value`,`label`)
VALUES (NULL,'cms','7200', 'CMS pages and blocks'), (NULL,'catalog','7200','Catalog (products and categories)');

-- DROP TABLE IF EXISTS {$this->getTable('advancedcache_blocks')};
CREATE TABLE {$this->getTable('advancedcache_blocks')} (
`block_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
 `identifier` TEXT,
 `description` VARCHAR( 255 ) DEFAULT NULL ,
 `block_class` TEXT,
 `block_name` VARCHAR( 255 ) DEFAULT NULL ,
 `special_configuration` TEXT,
 `expire` TEXT,
 `status` TINYINT( 1 ) NOT NULL DEFAULT  '1',
PRIMARY KEY (  `block_id` )
) ENGINE = INNODB DEFAULT CHARSET = utf8;

-- DROP TABLE IF EXISTS {$this->getTable('advancedcache_exception')};
CREATE TABLE {$this->getTable('advancedcache_exception')} (
`exception_id` INT( 11 ) NOT NULL AUTO_INCREMENT,
 `item_type` TEXT DEFAULT NULL,
 `item_id` VARCHAR( 255 ) DEFAULT NULL,
PRIMARY KEY (  `exception_id` )
) ENGINE = INNODB DEFAULT CHARSET = utf8;

");

// Create first admin blocks
$block = Mage::getModel('advancedcache/blocks');

// Popular tags
$data['identifier'] = 'tags_popular';
$data['description'] = 'Popular Tags';
$data['block_class']= 'Mage_Tag_Block_Popular';
$data['block_name'] = 'tags_popular';
$data['expire'] = 'cms';
$data['status'] = 0;

$block->setData($data);

$block->save();
// [end]

// Create 404 exception
$noRouteException = Mage::getModel('advancedcache/exception');
$noRouteException->setItemType('cms_page');
$noRouteException->setItemId('no-route');
$noRouteException->save();
// [end]

$installer->endSetup();
