<?php

$installer = $this;
$installer->startSetup();

$installer->run("
  ALTER TABLE {$installer->getTable('webtoprint/template')}
    ADD COLUMN `exist` bool NOT NULL default true AFTER `public`; ");

$installer->endSetup();

?>
