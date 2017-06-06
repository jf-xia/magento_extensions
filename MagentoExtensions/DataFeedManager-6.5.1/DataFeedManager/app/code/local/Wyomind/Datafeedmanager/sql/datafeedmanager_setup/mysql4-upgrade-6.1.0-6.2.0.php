<?php

$installer = $this;

$installer->startSetup();

$installer->run('RENAME TABLE ' . $this->getTable('datafeedmanager').' TO '. $this->getTable('datafeedmanager_configurations'));
$installer->run("ALTER TABLE {$this->getTable('datafeedmanager_configurations')} 
ADD  `feed_encoding` varchar(40) NOT NULL default 'UTF-8',
ADD  `feed_escape` char(3) NOT NULL,
ADD `feed_clean_data` int(1) NOT NULL default '1'
");

$installer->run('DROP TABLE IF EXISTS ' . $this->getTable('datafeedmanager_attributes'));
$installer->run('
CREATE TABLE IF NOT EXISTS `' . $this->getTable('datafeedmanager_attributes') . '` (
    `attribute_id` int(11) NOT NULL auto_increment,
    `attribute_name` varchar(100) NOT NULL,
    `attribute_script` text,
    PRIMARY KEY  (`attribute_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;');


$installer->run('DROP TABLE IF EXISTS ' . $this->getTable('datafeedmanager_options'));
$installer->run('
CREATE TABLE IF NOT EXISTS `' . $this->getTable('datafeedmanager_options') . '` (
    `option_id` int(11) NOT NULL auto_increment,
    `option_name` varchar(100) NOT NULL,
    `option_script` text,
    `option_param` int(1),
    PRIMARY KEY  (`option_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
');

$installer->endSetup();