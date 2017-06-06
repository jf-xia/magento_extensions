<?php
$installer = $this;
$installer->startSetup();

$installer->run("
   CREATE TABLE IF NOT EXISTS {$this->getTable('livechat')} (
      `livechat_id` int(11) NOT NULL auto_increment,
      `bubbleenable` varchar(100) NOT NULL,
      `bubbletext` varchar(100) NOT NULL,
      `bubbletitle` varchar(100) NOT NULL,
      `code` varchar(200) NOT NULL,
      `color` varchar(100) NOT NULL,
      `position` varchar(100) NOT NULL,
      `getvisitorinfo` varchar(100) NOT NULL,
      `greetings` text NOT NULL,
      `hideonoffline` varchar(100) NOT NULL,
      `lang` varchar(100) NOT NULL,
      `salt` varchar(200) NOT NULL,
      `theme` varchar(100) NOT NULL,
      `username` varchar(100) NOT NULL,
      `useSSL` varchar(100) NOT NULL,
      PRIMARY KEY  (`livechat_id`)
   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('livechat')} 
(`livechat_id`, `bubbleenable`, `bubbletext`, `bubbletitle`, `code`, `color`, `position`, `getvisitorinfo`, `greetings`, `hideonoffline`, `lang`, `salt`, `theme`, `username`, `useSSL`) VALUES
(1, 'checked', 'Click here to chat with us!', 'Questions?', '', '', 'br', 'yes', '{\"away\":{\"window\":\"If you leave a question or comment, our agents will be notified and will try to attend to you shortly =)\",\"bar\":\"Click here to chat\"},\"offline\":{\"window\":\"We are offline, but if you leave your message and contact details, we will try to get back to you =)\",\"bar\":\"Leave a message\"},\"online\":{\"window\":\"Leave a question or comment and our agents will try to attend to you shortly =)\",\"bar\":\"Click here to chat\"}}', 'disabled', 'el', '', '', '', '');
");

$installer->endSetup();
