<?php

$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE {$this->getTable('datafeedmanager')} 
ADD `cron_expr` VARCHAR(200) NOT NULL DEFAULT '* * * * *',
MODIFY `datafeedmanager_categories` LONGTEXT; ");

$installer->endSetup();
