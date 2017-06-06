<?php
/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
 /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 *****************************************/
 /* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
 /***************************************
 *         DISCLAIMER   *
 *****************************************/
 /* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 *****************************************************
 * @category   Belvg
 * @package    Belvg_FacebookFree
 * @copyright  Copyright (c) 2010 - 2011 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

class Belvg_FacebookFree_Block_Active extends Mage_Core_Block_Template {

    public function getAppId()
    {
        return Mage::getStoreConfig('facebookfree/settings/appid');
    }

    public function getSecretKey()
    {
        return Mage::getStoreConfig('facebookfree/settings/secret');
    }

    public function isActiveLike()
    {
        return Mage::getStoreConfig('facebookfree/like/enabled');
    }

    public function isFacesLikeActive()
    {
        return Mage::getStoreConfig('facebookfree/like/faces')?'true':'false';
    }

    public function getLikeWidth()
    {
        return Mage::getStoreConfig('facebookfree/like/width');
    }

    public function getLikeColor()
    {
        return Mage::getStoreConfig('facebookfree/like/color');
    }

    public function getLikeLayout()
    {
        return Mage::getStoreConfig('facebookfree/like/layout');
    }

    public function checkFbUser()
    {
	$user_id = Mage::getSingleton('customer/session')->getCustomer()->getId();
	$uid = 0;
	$db_read = Mage::getSingleton('core/resource')->getConnection('facebookfree_read');
	$tablePrefix = (string)Mage::getConfig()->getTablePrefix();
        
	$sql = 'SELECT `fb_id`
		FROM `'.$tablePrefix.'belvg_facebook_customer`
		WHERE `customer_id` = '.$user_id.'
		LIMIT 1';
	$data = $db_read->fetchRow($sql);
	if (count($data)) {
	  $uid = $data['fb_id'];
	}
	return $uid;
    }	
}