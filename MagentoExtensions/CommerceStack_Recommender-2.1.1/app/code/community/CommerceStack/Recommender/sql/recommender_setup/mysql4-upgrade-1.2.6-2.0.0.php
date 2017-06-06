<?php

$installer = $this;
$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('recommender/product_link')} ADD COLUMN `count` INT(10) NULL AFTER `position` ;

");