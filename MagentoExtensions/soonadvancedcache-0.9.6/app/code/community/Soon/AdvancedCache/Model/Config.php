<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G. 
 */
class Soon_AdvancedCache_Model_Config extends Mage_Core_Model_Abstract {
    
    const SPECIAL_CONFIG_CURRENT_CATEGORY = 'current_category';
    const SPECIAL_CONFIG_CURRENT_PRODUCT = 'current_product';
    const SPECIAL_CONFIG_PRODUCTS_LIST = 'products_list';
    const SPECIAL_CONFIG_PRODUCT_VIEW = 'product_view';
    const SPECIAL_CONFIG_BREADCRUMBS = 'breadcrumbs';
    
    const EXCEPTION_TYPE_CMS_PAGE = 'cms_page';
    

    /**
     * Set expiration lead time and save to database
     * 
     * @param string $tag
     * @param int $value 
     * @return Soon_AdvancedCache_Model_Config
     */
    public function setExpire($tag, $value) {
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $sql = "UPDATE  `advancedcache_config` SET `value` =  '{$value}' WHERE  `advancedcache_config`.`tag` = '{$tag}'";
        $write->query($sql);
        
        return $this;
    }

    /**
     * Retrieve expiration lead time from database
     * 
     * @param string $tag
     * @return int 
     */
    public function getExpire($tag) {
        if($tag == 'shortest') {
            return $this->getShortestExpire();
        }
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql = "SELECT * FROM `advancedcache_config` WHERE `tag` LIKE  '{$tag}'";
        $result = $read->fetchRow($sql);
        return $result['value'];
    }

    /**
     * Retrieve all configs
     * 
     * @return array
     */
    public function getAllExpires() {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql = "SELECT * FROM `advancedcache_config`";
        $result = $read->fetchAll($sql);
        return $result;
    }

    /**
     * Retrieve shortest lead time from all configs
     * 
     * @return int
     */
    public function getShortestExpire() {
        $expires = $this->getAllExpires();
        foreach ($expires as $expire) {
            $expiresValues[] = $expire['value'];
        }
        sort($expiresValues);
        return $expiresValues[0];
    }

    /**
     * Create config array
     * 
     * @return array
     */
    public function getExpireOptions() {
        $expires = $this->getAllExpires();
        $expireOptions = array();
        foreach ($expires as $expire) {
            $expireOptions[] = array(
                'value' => $expire['tag'],
                'label' => Mage::helper('advancedcache')->__($expire['label'])
            );
        }
        
        $expireOptions[] = array(
            'value' => 'shortest',
            'label' => Mage::helper('advancedcache')->__('Shortest')
        );
        
        return $expireOptions;
    }
    
    /**
     * Return exception types labels
     * 
     * @return array
     */
    public function getExceptionTypes() {
        $exceptionTypes = array(
            self::EXCEPTION_TYPE_CMS_PAGE => Mage::helper('advancedcache')->__('CMS Page'),
        );
        
        return $exceptionTypes;
    }

}
