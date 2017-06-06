<?php

/**
 *
 * Copyright CKApps.com
 * email: app@ckapps.com
 *
 */

$installer = $this;

$installer->startSetup();

$installer->run("
       CREATE TABLE order_trade_no(
     `out_trade_no` varchar(30) not null,
     `trade_no` varchar(30) not null
    ) ;
");

$installer->endSetup();