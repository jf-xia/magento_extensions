<?php

$installer = $this;

$installer->startSetup();

//create attribute set Groupdeal
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

$attributeSetId = $attributeSetModel->getId();


//create attribute featured,  endtime
$attributeFeaturedCode = 'groupdeal_featured';
$attributeEndTimeCode = 'groupdeal_endtime';

$attributeFeatured = Mage::getModel('eav/entity_attribute')->getCollection()
			->addFieldToFilter('entity_type_id', $entityTypeId)
			->addFieldToFilter('attribute_code', $attributeFeaturedCode)
			->getFirstItem();

if(!$attributeFeatured->getId()){
	$attributeFeatured->setEntityTypeId($entityTypeId)
						->setAttributeCode($attributeFeaturedCode)
						->setBackendType('int')
						->setFrontendInput('boolean')
						->setFrontendLabel('Is Featured Deal')
						->save();	
}

$attributeEndTime = Mage::getModel('eav/entity_attribute')->getCollection()
			->addFieldToFilter('entity_type_id', $entityTypeId)
			->addFieldToFilter('attribute_code', $attributeEndTimeCode)
			->getFirstItem();
			
if(!$attributeEndTime->getId()){
	$attributeEndTime->setEntityTypeId($entityTypeId)
						->setAttributeCode($attributeEndTimeCode)
						->setBackendType('text')
						->setFrontendInput('text')
						->setFrontendLabel('Groupdeal End Time')
						->save();
}


//add attribute to attribute set
$goupName = 'Groupdeal Info';
$installer->addAttributeGroup($entityTypeId, $attributeSetId, $goupName, 1);
$groupId = $installer->getAttributeGroupId($entityTypeId, $attributeSetId, $goupName);

if($groupId){
	if($attributeEndTime->getId())
		$installer->addAttributeToSet($entityTypeId,  $attributeSetId, $groupId, $attributeEndTime->getId());
	if($attributeFeatured->getId())
		$installer->addAttributeToSet($entityTypeId,  $attributeSetId, $groupId, $attributeFeatured->getId());
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
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `bought` int(10) NOT NULL default '0',
  `minimum_purchase` int(10) NOT NULL,
  `maximum_purchase` int(10) NOT NULL default '0',
  `deal_price` float NOT NULL,
  `deal_value` float NOT NULL,
  `created_time` datetime NOT NULL,
  `deal_status` tinyint(1) NOT NULL default '1',
  `is_sendmail_unreached` tinyint(1) NOT NULL default '0',
  `what_happen` text NOT NULL,
  `featured` tinyint(1) NOT NULL default '0',
  `url_key` varchar(250) NOT NULL default '',
  FOREIGN KEY (`product_id`) REFERENCES {$this->getTable('catalog/product')}  (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`deal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('groupdeal_image')};
CREATE TABLE {$this->getTable('groupdeal_image')} (
  `image_id` int(10) unsigned NOT NULL auto_increment,
  `deal_id` int(10) unsigned NOT NULL,
  `image_url` varchar(250)  NOT NULL,
  `sort_order` smallint(3) NOT NULL default '0',
  FOREIGN KEY (`deal_id`) REFERENCES {$this->getTable('groupdeal_deal')}  (`deal_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`image_id`)
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


DROP TABLE IF EXISTS {$this->getTable('groupdeal_subscriber')};
CREATE TABLE {$this->getTable('groupdeal_subscriber')} (
  `subscriber_id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(100) NOT NULL, 
  `categories` varchar(100),
  `price_from` float NOT NULL default '0',
  `price_to` float NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY (`subscriber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


");

$installer->endSetup(); 