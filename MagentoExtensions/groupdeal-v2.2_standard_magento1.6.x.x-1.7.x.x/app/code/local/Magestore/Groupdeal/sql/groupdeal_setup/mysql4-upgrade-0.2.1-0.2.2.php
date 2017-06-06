<?php

$installer = $this;

$installer->startSetup();
$installer->run("
	ALTER TABLE {$this->getTable('groupdeal_deal')} 
	ADD `option_product` tinyint(1) NULL DEFAULT '0' AFTER `url_key`; 
");
$installer->endSetup(); 