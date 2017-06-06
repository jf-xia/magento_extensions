<?php
$installer = $this;
$installer->startSetup();
$installer->run("
                DROP TABLE IF EXISTS {$this->getTable('tb_pccomments')};
		CREATE TABLE {$this->getTable('tb_pccomments')} (
                    idpccomments int(11) unsigned NOT NULL auto_increment, 
                    pccomm_idfeedback int(11) NULL,
                    pccomm_idorder int(11) NULL,
                    pccomm_idmagorder int(11) NULL,
                    pccomm_idparent int(11) NULL,
                    pccomm_idmagparent int(11) NULL,
                    pccomm_iduser int(11) NULL,
                    pccomm_idmaguser int(11) NULL,
                    pccomm_createddate datetime NULL,
                    pccomm_editeddate datetime NULL,
                    pccomm_ftype int(11) NULL,
                    pccomm_fstatus int(11) NULL,
                    pccomm_priority int(11) NULL,
                    pccomm_description text NULL,
                    pccomm_details text NULL,
                    idproduct int(11) NULL,
                    idmagproduct int(11) NULL,
                    pccomm_internalnotes text NULL,
                    notesreaded smallint(11) NULL,
                    notedate varchar(50) NULL,
                    pccomm_idproduct varchar(50) NULL,
                    pccomm_productdes varchar(256) NULL,
                    pccomm_keeplive int(11) NULL,
                    sourcetype int(11) NULL,
                    last_staff_id int(11) NULL,
                    idstore int(11) NULL,
                    PRIMARY KEY  (`idpccomments`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                DROP TABLE IF EXISTS {$this->getTable('tb_pcftypes')};
		CREATE TABLE {$this->getTable('tb_pcftypes')} (
                    pcftype_idtype int(11) unsigned NOT NULL auto_increment, 
                    pcftype_name varchar(100) NULL,
                    pcftype_img varchar(100) NULL,
                    pcftype_showimg int(11) NULL,
                    manager char(32) NULL,
                    displaytype int(11) NULL,
                    sortby int(11) NULL,
                    PRIMARY KEY  (`pcftype_idtype`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;

                DROP TABLE IF EXISTS {$this->getTable('tb_pcfstatus')};
		CREATE TABLE {$this->getTable('tb_pcfstatus')} (
                    pcfstat_idstatus int(11) unsigned NOT NULL auto_increment, 
                    pcfstat_name varchar(100) NULL,
                    pcfstat_img varchar(100) NULL,
                    pcfstat_bgcolor varchar(10) NULL,
                    pcfstat_showimg int(11) NULL,
                    PRIMARY KEY  (`pcfstat_idstatus`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;

                DROP TABLE IF EXISTS {$this->getTable('tb_pcpriority')};
		CREATE TABLE {$this->getTable('tb_pcpriority')} (
                    pcpri_idpri int(11) unsigned NOT NULL auto_increment, 
                    pcpri_name varchar(100) NULL,
                    pcpri_img varchar(100) NULL,
                    pcpri_showimg int(11) NULL,
                    PRIMARY KEY  (`pcpri_idpri`)
                )  ENGINE=InnoDB DEFAULT CHARSET=utf8;

                DROP TABLE IF EXISTS {$this->getTable('tb_pccomments_rank')};
                CREATE TABLE {$this->getTable('tb_pccomments_rank')} (
                    rank_mag_id int(11) unsigned NOT NULL auto_increment, 
                    rank_id int(11) NULL,
                    pccomm_idfeedback int(11) NULL,
                    id_mag_pccomments int(11) NULL,
                    updatedate datetime NULL,
                    rank_type int(11) NULL,
                    rank_point int(11) NULL,
                    PRIMARY KEY  (`rank_mag_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                ");

$installer->endSetup();
	 
/*
DROP TABLE IF EXISTS {$this->getTable('tb_csgroup')};
CREATE TABLE {$this->getTable('tb_csgroup')} (
      idcsgroup int(11) unsigned NOT NULL,
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
      rowid int(11) unsigned NOT NULL,
      sku varchar(50) NULL,
      PRIMARY KEY  (`idcsproduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('tb_csproductmap')};
CREATE TABLE {$this->getTable('tb_csproductmap')} (
      idproduct int(11) NULL,
      idcsgroup int(11) NULL,
      sortby int(11) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */