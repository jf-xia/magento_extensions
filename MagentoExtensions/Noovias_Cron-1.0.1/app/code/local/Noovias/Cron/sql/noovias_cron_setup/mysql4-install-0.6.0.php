<?php
$installer = $this;
$installer->startSetup();

$installer->run("
CREATE TABLE {$this->getTable('noovias_cron_processedjob')} (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` int(10) unsigned NOT NULL,
  `email_sent` boolean NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  ");

$installer->endSetup();
