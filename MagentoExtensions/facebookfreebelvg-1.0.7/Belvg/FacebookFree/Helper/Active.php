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
 * *************************************** */
/* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
  /***************************************
 *         DISCLAIMER   *
 * *************************************** */
/* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 * ****************************************************
 * @category   Belvg
 * @package    Belvg_FacebookFree
 * @copyright  Copyright (c) 2010 - 2011 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

class Belvg_FacebookFree_Helper_Active extends Mage_Core_Helper_Abstract {

    public function getAppId() {
        return Mage::getStoreConfig('facebookfree/settings/appid');
    }

    public function getSecretKey() {
        return Mage::getStoreConfig('facebookfree/settings/secret');
    }

    public function isActiveLike() {
        return Mage::getStoreConfig('facebookfree/like/enabled');
    }

    public function isFacesLikeActive() {
        return Mage::getStoreConfig('facebookfree/like/faces') ? 'true' : 'false';
    }

    public function getLikeWidth() {
        return Mage::getStoreConfig('facebookfree/like/width');
    }

    public function getLikeColor() {
        return Mage::getStoreConfig('facebookfree/like/color');
    }

    public function getLikeLayout() {
        return Mage::getStoreConfig('facebookfree/like/layout');
    }

    public function getProducts($order) {
        $db_read = Mage::getSingleton('core/resource')->getConnection('facebookfree_read');
        $tablePrefix = (string) Mage::getConfig()->getTablePrefix();

        $sql = 'SELECT `product_id` FROM `' . $tablePrefix . 'sales_flat_order_item` as i
                LEFT JOIN `' . $tablePrefix . 'sales_flat_order` as o ON o.`increment_id` = "' . $order . '"
                WHERE i.`order_id` = o.`entity_id` AND i.`parent_item_id` IS NULL';
        $data = $db_read->fetchAll($sql);
        return $data;
    }

    public function getLoginImg() {
        $img = Mage::getStoreConfig('facebookfree/settings/imglogin');
        if (empty($img)) {
            $img = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) .
                    'frontend/default/default/images/belvg/fb.gif';
        } else {
            $img = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) .
                    'facebookfree/' . $img;
        }
        return $img;
    }

    public function isActiveActivity()
    {
        return Mage::getStoreConfig('facebookfree/activity/enabled');
    }        
    
    public function getActivityWidth()
    {
        return Mage::getStoreConfig('facebookfree/activity/width');
    }    
    
    public function getActivityHeight()
    {
        return Mage::getStoreConfig('facebookfree/activity/height');
    }        
    
    public function getActivityHeader()
    {
        return Mage::getStoreConfig('facebookfree/activity/header')?'true':'false';
    }            
    
    public function getActivityColor()
    {
        return Mage::getStoreConfig('facebookfree/activity/color');
    }             
    
    public function getActivityRecommendations()
    {
        return Mage::getStoreConfig('facebookfree/activity/recommendations')?'true':'false';
    }    
    
    public function getActivityMaxage()
    {
        return Mage::getStoreConfig('facebookfree/activity/maxage');
    }           
        
}
