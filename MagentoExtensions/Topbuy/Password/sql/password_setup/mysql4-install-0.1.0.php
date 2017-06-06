<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
DROP TABLE IF EXISTS {$this->getTable('tb_recordpassword')};
CREATE TABLE {$this->getTable('tb_recordpassword')} (
                    rowid int(11)  unsigned NOT NULL auto_increment,
                    customeremail varchar(200) NULL,
                    customerpassword varchar(200) NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
	 