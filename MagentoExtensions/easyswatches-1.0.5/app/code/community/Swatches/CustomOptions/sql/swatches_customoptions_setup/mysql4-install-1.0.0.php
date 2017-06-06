<?php
$installer = $this;

$installer->startSetup();

if (!$installer->tableExists($installer->getTable('swatches_customoptions'))) {

$installer->run("
        
-- DROP TABLE IF EXISTS {$this->getTable('swatches_customoptions')};
CREATE TABLE IF NOT EXISTS {$this->getTable('swatches_customoptions')} (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Image ID',
  `option_type_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Option Type ID',
  `store_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Store ID',
  `image` varchar(255) DEFAULT NULL COMMENT 'Title',
  PRIMARY KEY (`image_id`),
  UNIQUE KEY `UNQ_SWATCHES_CUSTOMOPTIONS_OPTION_TYPE_ID_STORE_ID` (`option_type_id`,`store_id`),
  KEY `IDX_SWATCHES_CUSTOMOPTIONS_OPTION_TYPE_ID` (`option_type_id`),
  KEY `IDX_SWATCHES_CUSTOMOPTIONS_STORE_ID` (`store_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Custom Options Images Table';


ALTER TABLE `swatches_customoptions`
  ADD CONSTRAINT `FK_SWATCHES_CUSTOMOPTIONS_OPTION_TYPE_ID` FOREIGN KEY (`option_type_id`) REFERENCES `catalog_product_option_type_value` (`option_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_SWATCHES_CUSTOMOPTIONS_STORE_ID_CORE_STORE_STORE_ID` FOREIGN KEY (`store_id`) REFERENCES `core_store` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE;

");

}

$installer->endSetup();
