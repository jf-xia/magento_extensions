<?php

$installer = $this;

$installer->startSetup();

// Drop pre 0.9.3 tables that do not have correct prefix
if((string)Mage::getConfig()->getTablePrefix() != '')
{
    $installer->run("DROP TABLE IF EXISTS recommender_product_link");
}

$installer->run("

CREATE TABLE IF NOT EXISTS {$this->getTable('recommender/product_link')} (
  `link_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(10) unsigned NOT NULL default '0',
  `linked_product_id` int(10) unsigned NOT NULL default '0',
  `position` int(10) unsigned NOT NULL default '0',
  `link_type_id` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`link_id`),
  KEY `FK_LINK_PRODUCT` (`product_id`),
  KEY `FK_LINKED_PRODUCT` (`linked_product_id`),
  KEY `FK_PRODUCT_LINK_TYPE` (`link_type_id`),
  UNIQUE KEY (`link_type_id`, `product_id`, `position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Related products';

");


