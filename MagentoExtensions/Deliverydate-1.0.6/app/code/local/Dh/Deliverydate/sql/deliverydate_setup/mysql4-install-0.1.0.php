<?php

$installer = $this;

$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
	$installer->run("
		DROP TABLE IF EXISTS {$this->getTable('delivery_date')};

		CREATE TABLE {$this->getTable('delivery_date')} (
			`delivery_date_id` int(7) unsigned NOT NULL auto_increment,
			`date` text NOT NULL,
			PRIMARY KEY  (`delivery_date_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	");
$installer->endSetup();


$setup = new Mage_Sales_Model_Mysql4_Setup('sales_setup');
$setup->getConnection()->addColumn(
        $setup->getTable('sales_flat_quote'),
        'delivery_date',
        'text NULL DEFAULT NULL'
    );
	
$setup->addAttribute('quote', 'delivery_date', array('type' => 'varchar', 'visible' => false));
$installer->addAttribute('order', 'delivery_date_id', array('type' => 'int', 'visible' => false, 'required' => false));