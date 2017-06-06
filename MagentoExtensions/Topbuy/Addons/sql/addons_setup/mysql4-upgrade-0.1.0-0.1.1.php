<?php
$installer = $this;
$installer->startSetup();
$installer->run("
        
DROP TABLE IF EXISTS {$this->getTable('tb_warrantymap')};
CREATE TABLE {$this->getTable('tb_warrantymap')}(
        rowid int(11) unsigned NOT NULL auto_increment,
	idproduct int(11) NULL,
	idmagproduct int(11) NULL,
	pricefrom decimal(10,2) NULL,
	priceto decimal(10,2) NULL,
	period int(11) NULL,
        PRIMARY KEY  (`rowid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('tb_warrantyrecord')};
CREATE TABLE {$this->getTable('tb_warrantyrecord')}(
        rowid int(11) unsigned NOT NULL auto_increment,
	warrantyproduct int(11) NULL,
	itemproduct int(11) NULL,
	warrantyproductmag int(11) NULL,
	itemproductmag int(11) NULL,
	entrydate datetime NULL,
	serialsno varchar(100) NULL,
        PRIMARY KEY  (`rowid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();
	 