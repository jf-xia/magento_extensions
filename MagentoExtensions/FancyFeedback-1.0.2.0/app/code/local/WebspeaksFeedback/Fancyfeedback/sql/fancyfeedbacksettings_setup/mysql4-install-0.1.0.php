<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('fancyfeedbacksettings')};
CREATE TABLE `fancyfeedbacksettings` (
  `fancyfeedbacksettings_id` INT(11) NOT NULL AUTO_INCREMENT,
  `enabled` ENUM('y','n') DEFAULT 'y',
  PRIMARY KEY  (`fancyfeedbacksettings_id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1;
    ");

$installer->endSetup(); 