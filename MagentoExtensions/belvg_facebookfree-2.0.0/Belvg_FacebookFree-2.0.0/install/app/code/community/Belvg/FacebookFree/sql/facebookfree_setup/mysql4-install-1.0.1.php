<?php

$installer = $this;

$installer->startSetup();
$table = $this->getTable('facebookfree/facebookfree');

$installer->run("

CREATE TABLE IF NOT EXISTS `{$table}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  `website_id` smallint(5) unsigned NOT NULL,
  `fb_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_id` (`customer_id`),
  UNIQUE KEY `store_id` (`store_id`,`website_id`,`fb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `{$table}`
  ADD CONSTRAINT `belvg_facebook_customer_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `belvg_facebook_customer_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `{$this->getTable('customer_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

");



$installer->endSetup();