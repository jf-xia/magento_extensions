<?php 
$installer = $this;
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('tb_payment_verify_record')};
CREATE TABLE {$this->getTable('tb_payment_verify_record')} (
      id_paymentverify int(11) unsigned NOT NULL auto_increment,
      idcustomer int(11) NULL,
	  idorder int(11) NULL,
      entry_date datetime NULL,
	  money_input int(11) NULL,
	  verify_flag int(11) NULL,
      PRIMARY KEY  (`id_paymentverify`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8; "); 
$installer->endSetup();
 