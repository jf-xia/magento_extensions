<?php

$installer = $this;

$installer->startSetup();

$installer->run("

INSERT INTO {$this->getTable('directory_country_region')} (`region_id`,`country_id`,`code`,`default_name`) VALUES (NULL, 'CN', 'BJ', '北京'),(NULL, 'CN', 'GD', '广东'),(NULL, 'CN', 'SH', '上海'),(NULL, 'CN', 'TJ', '天津'),(NULL, 'CN', 'HE', '河北'),(NULL, 'CN', 'SX', '山西'),(NULL, 'CN', 'NM', '内蒙古'),(NULL, 'CN', 'LN', '辽宁'),(NULL, 'CN', 'JL', '吉林'),(NULL, 'CN', 'HL', '黑龙江'),(NULL, 'CN', 'JS', '江苏'),(NULL, 'CN', 'ZJ', '浙江'),(NULL, 'CN', 'AH', '安徽'),(NULL, 'CN', 'FJ', '福建'),(NULL, 'CN', 'JX', '江西'),(NULL, 'CN', 'SD', '山东'),(NULL, 'CN', 'HA', '河南'),(NULL, 'CN', 'HB', '湖北'),(NULL, 'CN', 'HN', '湖南'),(NULL, 'CN', 'GX', '广西'),(NULL, 'CN', 'HI', '海南'),(NULL, 'CN', 'CQ', '重庆'),(NULL, 'CN', 'SC', '四川'),(NULL, 'CN', 'GZ', '贵州'),(NULL, 'CN', 'YN', '云南'),(NULL, 'CN', 'XZ', '西藏'),(NULL, 'CN', 'SN', '陕西'),(NULL, 'CN', 'GS', '甘肃'),(NULL, 'CN', 'QH', '青海'),(NULL, 'CN', 'NX', '宁夏'),(NULL, 'CN', 'XJ', '新疆'),(NULL, 'CN', 'HK', '香港'),(NULL, 'CN', 'AM', '澳门'),(NULL, 'CN', 'TW', '台湾地区');

    ");

$installer->endSetup(); 