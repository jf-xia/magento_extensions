<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('blog')};
CREATE TABLE {$this->getTable('blog')} (
`post_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
`cat_id` smallint( 11 ) NOT NULL default '0',
`title` varchar( 255 ) NOT NULL default '',
`content` text NOT NULL ,
`status` smallint( 6 ) NOT NULL default '0',
`created_time` datetime default NULL ,
`update_time` datetime default NULL ,
`identifier` varchar( 255 ) NOT NULL default '',
`user` varchar( 255 ) NOT NULL default '',
`update_user` varchar( 255 ) NOT NULL default '',
`meta_keywords` text NOT NULL ,
`meta_description` text NOT NULL ,
PRIMARY KEY ( `post_id` ) ,
UNIQUE KEY `identifier` ( `identifier` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO {$this->getTable('blog')} (`post_id` ,`cat_id`, `title` ,`content` ,`status` ,`created_time` ,`update_time` ,`identifier` ,`user` ,`update_user` ,`meta_keywords` ,`meta_description`)
VALUES (NULL ,'0', 'Hello World', 'Welcome to Lazzymonks Magento Blog. This is your first post. Edit or delete it, then start blogging!', '1', NOW( ) , NOW( ) , 'Hello', 'Joe Blogs', 'Joe Blogs', 'Keywords', 'Description');

DROP TABLE IF EXISTS {$this->getTable('blog_comment')};
CREATE TABLE {$this->getTable('blog_comment')} (
`comment_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
`post_id` smallint( 11 ) NOT NULL default '0',
`comment` text NOT NULL ,
`status` smallint( 6 ) NOT NULL default '0',
`created_time` datetime default NULL ,
`user` varchar( 255 ) NOT NULL default '',
`email` varchar( 255 ) NOT NULL default '',
PRIMARY KEY ( `comment_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO {$this->getTable('blog_comment')} (`comment_id` ,`post_id` ,`comment` ,`status` ,`created_time` ,`user` ,`email`)
VALUES (NULL , '1', 'This is the first comment. It can be edited, deleted or set to unapproved so it is not displayed. This can be done in the admin panel.', '2', NOW( ) , 'Joe Blogs', 'joe@blogs.com');

DROP TABLE IF EXISTS {$this->getTable('blog_cat')};
CREATE TABLE {$this->getTable('blog_cat')} (
`cat_id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
`title` varchar( 255 ) NOT NULL default '',
`identifier` varchar( 255 ) NOT NULL default '',
`sort_order` tinyint ( 6 ) NOT NULL ,
`meta_keywords` text NOT NULL ,
`meta_description` text NOT NULL ,
PRIMARY KEY ( `cat_id` )
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO {$this->getTable('blog_cat')} (
`cat_id` ,
`title`,
`identifier`
)
VALUES (
NULL , 'News', 'news'
);
");

$installer->endSetup(); 