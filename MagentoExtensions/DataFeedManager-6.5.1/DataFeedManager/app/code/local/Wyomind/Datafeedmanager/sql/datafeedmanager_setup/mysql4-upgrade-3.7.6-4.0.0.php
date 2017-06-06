<?php

$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE {$this->getTable('datafeedmanager')} 
ADD feed_extraheader TEXT,
MODIFY `cron_expr` varchar(900) NOT NULL DEFAULT '{\"days\":[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\",\"Sunday\"],\"hours\":[\"00:00\",\"04:00\",\"08:00\",\"12:00\",\"16:00\",\"20:00\"]}',
MODIFY `feed_updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP; 
;");

$installer->endSetup();