<?php
$installer = $this;
$installer->startSetup();
$installer->run("alter table newsletter_subscriber add column subscribe_type int(11);

                DROP TABLE IF EXISTS {$this->getTable('tb_prddescription')};
                CREATE TABLE {$this->getTable('tb_prddescription')} (
                    rowid           int(11) unsigned NOT NULL auto_increment, 
                    supplier      int(11) NULL,
                    categoryid       int(11) NULL, 
                    description1    text NULL,
                    description2    text NULL,
                    description3    text NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                ");

$installer->endSetup();
	 