<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 
CREATE TABLE  {$this->getTable('adminlogger_log')} (
 `al_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `al_date` DATETIME NOT NULL ,
 `al_user` VARCHAR( 25 ) NOT NULL ,
 `al_object_type` VARCHAR( 50 ) NOT NULL ,
 `al_object_id` INT NOT NULL ,
 `al_object_description` VARCHAR( 255 ) NOT NULL ,
 `al_description` TEXT NOT NULL,
 al_action_type varchar(25) NOT NULL
 
) ENGINE = MYISAM ;

    ");
 
$installer->endSetup();
