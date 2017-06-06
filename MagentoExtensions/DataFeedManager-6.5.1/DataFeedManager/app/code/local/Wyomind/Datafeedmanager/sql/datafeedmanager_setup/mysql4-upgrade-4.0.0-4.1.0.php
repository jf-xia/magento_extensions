<?php

$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE {$this->getTable('datafeedmanager')} ADD datafeedmanager_category_filter INT(1) DEFAULT 1;");


$installer->endSetup();