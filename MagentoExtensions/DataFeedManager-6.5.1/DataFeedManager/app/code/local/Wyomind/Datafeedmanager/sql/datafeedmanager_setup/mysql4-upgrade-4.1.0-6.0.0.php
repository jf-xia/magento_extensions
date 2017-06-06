<?php

$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE {$this->getTable('datafeedmanager')} 
        ADD `ftp_enabled` INT(1) DEFAULT '0',
        ADD `ftp_host` VARCHAR(300) DEFAULT NULL,
        ADD `ftp_login` VARCHAR(300) DEFAULT NULL,
        ADD `ftp_password` VARCHAR(300) DEFAULT NULL,
        ADD `ftp_active` INT(1) DEFAULT '0',
        ADD `ftp_dir` VARCHAR(300) DEFAULT NULL;
");


$installer->endSetup();