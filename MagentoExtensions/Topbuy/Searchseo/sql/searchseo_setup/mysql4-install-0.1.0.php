<?php

$installer = $this;
$installer->startSetup();
$sql = <<<SQLTEXT


                DROP TABLE IF EXISTS {$this->getTable('tb_searchseo')};
                CREATE TABLE {$this->getTable('tb_searchseo')} (
                    rowid           int(11) unsigned NOT NULL auto_increment, 
                    searchtitle     varchar(50) NULL,
                    categoryid      int(11) NULL, 
                    relid           int(11) NULL, 
                    metatitle       text NULL,
                    metadescription text NULL,
                    metakeywords    text NULL,
                    PRIMARY KEY  (`rowid`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQLTEXT;

$installer->run($sql);

$installer->endSetup();

