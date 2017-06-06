<?php

$installer = $this;

$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
	$installer->run("
		DROP TABLE IF EXISTS {$this->getTable('delivery_note')};

		CREATE TABLE {$this->getTable('delivery_note')} (
			`delivery_note_id` int(7) unsigned NOT NULL auto_increment,
			`note` text NOT NULL,
			`time` text NOT NULL,
			PRIMARY KEY  (`delivery_note_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	");
$installer->endSetup();


$setup = new Mage_Sales_Model_Mysql4_Setup('sales_setup');
$setup->getConnection()->addColumn(
        $setup->getTable('sales_flat_quote'),
        'delivery_note',
        'text NULL DEFAULT NULL'
    );
$setup->getConnection()->addColumn(
        $setup->getTable('sales_flat_quote'),
        'delivery_time',
        'text NULL DEFAULT NULL'
    );
	
$setup->addAttribute('quote', 'delivery_time', array('type' => 'varchar', 'visible' => false));
$setup->addAttribute('quote', 'delivery_note', array('type' => 'varchar', 'visible' => false));
$installer->addAttribute('order', 'delivery_note_id', array('type' => 'int', 'visible' => false, 'required' => false));