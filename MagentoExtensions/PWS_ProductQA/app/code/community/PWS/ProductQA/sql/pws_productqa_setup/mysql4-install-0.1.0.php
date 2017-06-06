<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('pws_productqa')};
CREATE TABLE {$this->getTable('pws_productqa')} (
  `productqa_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',  
  `question` text NOT NULL default '',
  `answer` text NOT NULL default '',
  `status` enum('public','hidden') default 'public',
  `ip_address` varchar(255) NOT NULL default '',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `product_id` int unsigned not null,
  `created_on` datetime,
  `answered_on` datetime,
  PRIMARY KEY (`productqa_id`),
  CONSTRAINT FOREIGN KEY (`product_id`) REFERENCES {$this->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();
