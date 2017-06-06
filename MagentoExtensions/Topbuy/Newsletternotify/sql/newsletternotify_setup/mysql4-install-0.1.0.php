<?php
$installer = $this;
$installer->startSetup();
$installer->run("
            DROP TABLE IF EXISTS {$this->getTable('tb_newsletter')};
            CREATE TABLE {$this->getTable('tb_newsletter')} (
                rowid int(11) unsigned NOT NULL auto_increment,
                idproduct int(11) NULL,
                idcustomer int(11) NULL,
                syncflag int(11) NULL,
                entrydate datetime NULL,
                PRIMARY KEY  (`rowid`)
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            DROP TABLE IF EXISTS {$this->getTable('tb_newsletterrecord')};
            CREATE TABLE {$this->getTable('tb_newsletterrecord')} (
                rowid int(11) unsigned NOT NULL auto_increment,
                email varchar(50) NULL,
                idcustomer int(11) NULL,
                subscribe_type int(11) NULL,
                reason varchar(300) NULL,
                entrydate datetime NULL,
                PRIMARY KEY  (`rowid`)
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;

            DROP TABLE IF EXISTS {$this->getTable('tb_newsletterheader')};
            CREATE TABLE {$this->getTable('tb_newsletterheader')} (
                newsletterheadid int(11) unsigned NOT NULL auto_increment,
                senddate datetime NULL,
                modifydate datetime NULL,
                staffid int(11) NULL,
                newssubject varchar(300) NULL,
                stealdescription varchar(300) NULL,
                updateflag int(11) NULL,
                weeklydealskustring varchar(500) NULL,
                weeklydealtitlestring varchar(2000) NULL,
                idstore int(11) NULL,
                weeklydealpromotionstring varchar(2000) NULL,
                confirmed int(11) NULL,
                sendstaffid int(11) NULL,
                sendtime datetime NULL,
                PRIMARY KEY  (`newsletterheadid`)
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
            DROP TABLE IF EXISTS {$this->getTable('tb_emailsendbuffer')};
            CREATE TABLE {$this->getTable('tb_emailsendbuffer')} (
                rowid int(11) unsigned NOT NULL auto_increment,
                entrydate datetime NULL,
                fromname varchar(100) NULL,
                fromemail varchar(150) NULL,
                toemail varchar(150) NULL,
                subject varchar(200) NULL,
                body longtext NULL,
                attachment varchar(300) NULL,
                updateflag int(11) NULL,
                senddate datetime NULL,
                emailtype int(11) NULL,
                schedulesenddate datetime NULL,
                toname varchar(150) NULL,
                PRIMARY KEY  (`rowid`)
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                ");

$installer->endSetup();
	 
