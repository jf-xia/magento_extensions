<?php
$installer = $this;
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('tb_csgroup')};
CREATE TABLE {$this->getTable('tb_csgroup')} (
      idcsgroup int(11) unsigned NOT NULL auto_increment,
      csgroupname varchar(300) NULL,
      csentrydate datetime NULL,
      PRIMARY KEY  (`idcsgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
DROP TABLE IF EXISTS {$this->getTable('tb_csgroupproduct')};
CREATE TABLE {$this->getTable('tb_csgroupproduct')} (
      idcsproduct int(11) NULL,
      idcsgroup int(11) NULL,
      discount decimal(10,2) NULL,
      displayname varchar(100) NULL,
      sortby int(11) NULL,
      rowid int(11) unsigned NOT NULL auto_increment,
      sku varchar(50) NULL,
      PRIMARY KEY  (`rowid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('tb_csproductmap')};
CREATE TABLE {$this->getTable('tb_csproductmap')} (
      rowid int(11) unsigned NOT NULL auto_increment,
      idproduct int(11) NULL,
      idcsgroup int(11) NULL,
      sortby int(11) NULL,
      PRIMARY KEY  (`rowid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$installer->endSetup();
	 