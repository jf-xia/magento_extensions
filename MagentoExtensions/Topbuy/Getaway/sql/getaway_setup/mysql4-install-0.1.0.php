<?php
$installer = $this;
$installer->startSetup();
$installer->run("
                DROP TABLE IF EXISTS {$this->getTable('tb_edm_landing_record')};
                CREATE TABLE {$this->getTable('tb_edm_landing_record')} (
                    rowid int(11) unsigned NOT NULL auto_increment,
                    email varchar(500) NULL,
                    firstname varchar(100) NULL,
                    comments text NULL,
                    wherecomefrom varchar(50) NULL,
                    entrydate datetime NULL,
                    ipaddress varchar(50) NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;");
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 