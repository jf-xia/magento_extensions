<?php

$installer = $this;

$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
	$installer->run("
		DROP TABLE IF EXISTS {$this->getTable('checklist_checknote')};

		CREATE TABLE {$this->getTable('checklist_checknote')} (
			`checklist_checknote_id` int(7) unsigned NOT NULL auto_increment,
			`customer_id` int NOT NULL,
			`title` varchar(500) NOT NULL,
			`checknote` varchar(500) NOT NULL,
			PRIMARY KEY  (`checklist_checknote_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	");
$installer->endSetup();

/**
$setup = new Mage_Sales_Model_Mysql4_Setup('sales_setup');
$setup->getConnection()->addColumn(
        $setup->getTable('sales_flat_quote'),
        'checklist_checknote',
        'text NULL DEFAULT NULL'
    );
	
$setup->addAttribute('quote', 'checklist_checknote', array('type' => 'varchar', 'visible' => false));
$installer->addAttribute('order', 'checklist_checknote_id', array('type' => 'int', 'visible' => false, 'required' => false));

**/