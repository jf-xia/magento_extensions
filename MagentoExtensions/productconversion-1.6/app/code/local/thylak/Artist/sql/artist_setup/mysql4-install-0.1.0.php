<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * Table Creation - create table for the artist and artwork details
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('artist')};
CREATE TABLE {$this->getTable('artist')} (
  `artist_id` int(11) unsigned NOT NULL auto_increment,
  `firstname` varchar(255) NOT NULL default '',
  `lastname` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`artist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");
	
	$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('artwork')};
CREATE TABLE {$this->getTable('artwork')} (
  `artwork_id` int(11) unsigned NOT NULL auto_increment,
  `artist_id` int(11) NOT NULL,
  `imagename` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `status` tinyint(1) NOT NULL default 0,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`artwork_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 