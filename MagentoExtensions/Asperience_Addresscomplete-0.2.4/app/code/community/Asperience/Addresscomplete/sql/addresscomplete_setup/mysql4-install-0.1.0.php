<?php
/**
 * @category   ASPerience
 * @package    Asperience_Addresscomplete
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
set_time_limit(0);
$installer = $this;

$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('directory_country_city')};
CREATE TABLE {$this->getTable('directory_country_city')} (
  `city_id` mediumint(8)  NOT NULL AUTO_INCREMENT,
  `country_id` varchar(4) NOT NULL default '0',
  `latitude` varchar(32) NOT NULL default '',
  `longitude` varchar(32) NOT NULL default '',
  `region_code` varchar(8) NOT NULL default '0',
  `zip_code` varchar(10) NOT NULL default '',
  `default_name` varchar(255) default NULL,
  PRIMARY KEY  (`city_id`),
  KEY `FK_CITY_COUNTRY` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Country Cities'");


$installer->endSetup(); 
