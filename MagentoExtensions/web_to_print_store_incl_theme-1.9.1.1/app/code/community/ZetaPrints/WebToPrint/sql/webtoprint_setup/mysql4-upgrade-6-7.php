<?php

$installer = $this;
$installer->startSetup();

$installer->run(
  "DROP TABLE IF EXISTS `zetaprints_cookies`;
  CREATE TABLE `zetaprints_cookies` (
    `user_id` VARCHAR(36) NOT NULL,
    `pass` VARCHAR(6),
    PRIMARY KEY (`user_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;" );

$installer->endSetup();

?>
