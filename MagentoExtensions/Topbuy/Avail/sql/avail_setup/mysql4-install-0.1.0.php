<?php
$installer = $this;
$installer->startSetup();
$installer->run("
");
//CREATE TABLE `tb_sync_product_avail` ( 
//`rowid` int(11) unsigned NOT NULL auto_increment, 
//`idsource` int(11) DEFAULT NULL, 
//`idrefer` int(11) DEFAULT NULL, 
//`referType` int(11) DEFAULT NULL, 
//`sourceType` int(11) DEFAULT NULL, 
//`position` int(11) DEFAULT NULL, 
//`keywords` varchar(500) DEFAULT NULL,
//PRIMARY KEY  (`rowid`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8;
//demo DROP TABLE IF EXISTS `tb_sync_product_avail`;
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 