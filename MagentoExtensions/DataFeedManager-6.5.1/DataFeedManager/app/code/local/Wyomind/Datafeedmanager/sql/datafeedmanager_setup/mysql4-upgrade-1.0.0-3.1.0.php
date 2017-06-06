<?php

$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE {$this->getTable('datafeedmanager')} ADD `datafeedmanager_categories`  VARCHAR(200); ");

$installer->endSetup();