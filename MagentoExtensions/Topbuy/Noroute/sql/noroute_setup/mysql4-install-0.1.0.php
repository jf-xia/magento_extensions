<?php
$installer = $this;
$installer->startSetup();
$installer->run("
                DROP TABLE IF EXISTS {$this->getTable('tb_norouterecord')};
                CREATE TABLE {$this->getTable('tb_norouterecord')} (
                    rowid int(11) unsigned NOT NULL auto_increment, 
                    entrydate datetime NULL,
                    urlnoroute varchar(550) NULL,
                    ipnoroute varchar(50) NULL,
                    PRIMARY KEY  (`rowid`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 