<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('fancyfeedback')};
CREATE TABLE {$this->getTable('fancyfeedback')} (
  `fancyfeedback_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `comment` text NOT NULL,
  `ip` varchar(30) default '',
  `created_time` datetime default NULL,
  `reply` text,
  `reply_time` datetime default NULL,
  PRIMARY KEY  (`fancyfeedback_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

$installer->endSetup(); 