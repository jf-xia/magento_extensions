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


$installer->run("
CREATE TABLE {$this->getTable('noovias_cron_schedule_config')} (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `job_code` varchar(255) NOT NULL,
  index(`job_code`),
  `status` varchar(32),
  `cron_expr` varchar(255),
  `created` timestamp NULL default NULL,
  `updated` timestamp NULL default NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) unsigned,
  `updated_by` int(10) unsigned,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  "
);

$installer->endSetup();
