<?php

$installer = $this;
$installer->startSetup();

$installer->run("
  DROP TABLE IF EXISTS `{$installer->getTable('webtoprint/template')}`;
  CREATE TABLE `{$installer->getTable('webtoprint/template')}` (
    `template_id` int(11) NOT NULL auto_increment,
    `guid` varchar(36),
    `catalog_guid` varchar(36),
    `title` text,
    `link` text,
    `description` text,
    `thumbnail` text,
    `image` text,
    `date` timestamp,
    `public` bool,
    `xml` text,
    PRIMARY KEY  (`template_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;" );

$installer->endSetup();

?>
