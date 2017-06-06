<?php
/**
 * @var Mage_Catalog_Model_Resource_Eav_Mysql4_Setup $installer
 */
$installer = $this;
$config = Mage::getConfig()->getNode();
$table = $installer->getTable('ordercomments/customer_comments');
$historyTable = $installer->getTable('sales/order_status_history');
$customerTable = $installer->getTable('customer/entity');
$sql = "
  DROP TABLE IF EXISTS `{$table}`;
  CREATE TABLE `{$table}` (
    `entity_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `comment_id` int(10) UNSIGNED NOT NULL,
    `customer_id` int(10) UNSIGNED NULL,
    PRIMARY KEY  (`entity_id`),
    KEY (`comment_id`),
    KEY (`customer_id`),
    CONSTRAINT FOREIGN KEY (`comment_id`) REFERENCES `{$historyTable}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (`customer_id`) REFERENCES `{$customerTable}` (`entity_id`) ON DELETE SET NULL ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
$installer->run($sql);

$installer->endSetup();
