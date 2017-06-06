<?php

$installer = $this;

$installer->startSetup();

//create attribute set Groupdeal
$entityType = Mage::getSingleton('eav/entity_type')->loadByCode('catalog_product');
$entityTypeId = $entityType->getId();

$attributeSetName = 'Groupdeal';
$attributeSetId = Mage::getModel('eav/entity_setup')->getAttributeSetId($entityTypeId, $attributeSetName);

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
ALTER TABLE {$this->getTable('groupdeal_deal')} 
ADD `featured` tinyint(1) NOT NULL DEFAULT '0' AFTER `what_happen`; 

ALTER TABLE {$this->getTable('groupdeal_deal')} 
ADD `url_key` varchar(250) NOT NULL DEFAULT '' AFTER `featured`;

ALTER TABLE {$this->getTable('groupdeal_deal')} 
DROP COLUMN `expired_coupon`, DROP COLUMN `custom_coupon`, DROP COLUMN `coupons_code`;

DROP TABLE IF EXISTS {$this->getTable('groupdeal_subscriber')};
CREATE TABLE {$this->getTable('groupdeal_subscriber')} (
  `subscriber_id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(100) NOT NULL, 
  `categories` varchar(100),
  `price_from` float NOT NULL default '0',
  `price_to` float NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY (`subscriber_id`)
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

");

$installer->endSetup(); 