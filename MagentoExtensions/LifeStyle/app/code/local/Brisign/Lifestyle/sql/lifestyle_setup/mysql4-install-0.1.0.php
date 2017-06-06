<?php

$installer = $this;

$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
	$installer->run("
		DROP TABLE IF EXISTS {$this->getTable('lifestyle_favorite')};

		CREATE TABLE {$this->getTable('lifestyle_favorite')} (
			`lifestyle_favorite_id` int(7) unsigned NOT NULL auto_increment,
			`customer_id` int NOT NULL,
			`title` varchar(500) NOT NULL,
			`url` varchar(500) NOT NULL,
			PRIMARY KEY  (`lifestyle_favorite_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	");
$installer->endSetup();

/**
$setup = new Mage_Sales_Model_Mysql4_Setup('sales_setup');
$setup->getConnection()->addColumn(
        $setup->getTable('sales_flat_quote'),
        'lifestyle_favorite',
        'text NULL DEFAULT NULL'
    );
	
$setup->addAttribute('quote', 'lifestyle_favorite', array('type' => 'varchar', 'visible' => false));
$installer->addAttribute('order', 'lifestyle_favorite_id', array('type' => 'int', 'visible' => false, 'required' => false));

**/