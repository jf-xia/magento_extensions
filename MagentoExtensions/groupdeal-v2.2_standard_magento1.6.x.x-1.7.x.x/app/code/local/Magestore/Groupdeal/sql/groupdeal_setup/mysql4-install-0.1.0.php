<?php

$installer = $this;

$installer->startSetup();

$entityType = Mage::getSingleton('eav/entity_type')->loadByCode('catalog_product');
$entityTypeId = $entityType->getId();

$attributeSetName = 'Groupdeal';
$defaultSetId = Mage::getResourceModel('catalog/setup', 'core_setup')->getAttributeSetId($entityTypeId, 'Default');

$attributeSetModel = Mage::getModel('eav/entity_attribute_set')->load($attributeSetName,"attribute_set_name");

if(!$attributeSetModel->getId()){
	$attributeSetModel->setEntityTypeId($entityTypeId)
					  ->setAttributeSetName($attributeSetName);
	try{
		$attributeSetModel->save();
	}
	catch(Mage_Core_Exception $e){

	}catch (Exception $e) {}

	$attributeSetModel  = Mage::getModel('eav/entity_attribute_set')->load($attributeSetModel->getId());
	$attributeSetModel->initFromSkeleton($defaultSetId)
					  ->save();
}


$installer->run("

DROP TABLE IF EXISTS {$this->getTable('groupdeal_deal')};
CREATE TABLE {$this->getTable('groupdeal_deal')} (
  `deal_id` int(10) unsigned NOT NULL auto_increment,
  `product_id` int(11) unsigned  NOT NULL,
  `deal_title` varchar(250) NOT NULL,
  `image_url` varchar(250) NOT NULL,
  `short_description` text NOT NULL,
  `full_description` text NULL,
  `the_fine_print` text NULL,
  `highlights` text NULL,
  `vendor_name` varchar(250) NOT NULL,
  `expired_coupon` datetime NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `custom_coupon` tinyint(1) NOT NULL default '0',
  `coupons_code` text NULL,
  `bought` int(10) NOT NULL default '0',
  `minimum_purchase` int(10) NOT NULL,
  `maximum_purchase` int(10) NOT NULL default '0',
  `deal_price` float NOT NULL,
  `deal_value` float NOT NULL,
  `created_time` datetime NOT NULL,
  `deal_status` tinyint(1) NOT NULL default '1',
  `is_sendmail_unreached` tinyint(1) NOT NULL default '0',
  `what_happen` text NOT NULL,
  FOREIGN KEY (`product_id`) REFERENCES {$this->getTable('catalog/product')}  (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`deal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('groupdeal_productlist')};
CREATE TABLE {$this->getTable('groupdeal_productlist')} (
  `productlist_id` int(10) unsigned NOT NULL auto_increment,
  `deal_id` int(10) unsigned NOT NULL,
  `product_id` int(11) unsigned  NOT NULL, 
  FOREIGN KEY (`deal_id`) REFERENCES {$this->getTable('groupdeal_deal')} (`deal_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES {$this->getTable('catalog/product')}  (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`productlist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('groupdeal_orderlist')};
CREATE TABLE {$this->getTable('groupdeal_orderlist')} (
  `orderlist_id` int(10) unsigned NOT NULL auto_increment,
  `deal_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned  NOT NULL,
  `quantity` tinyint(3) unsigned NOT NULL, 
  `coupon_code` text NULL,
  FOREIGN KEY (`deal_id`) REFERENCES {$this->getTable('groupdeal_deal')} (`deal_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`order_id`) REFERENCES {$this->getTable('sales/order')}  (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT uq_order UNIQUE (deal_id, order_id),
  PRIMARY KEY (`orderlist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup(); 