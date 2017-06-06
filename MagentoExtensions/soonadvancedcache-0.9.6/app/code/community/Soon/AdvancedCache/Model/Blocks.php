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
class Soon_AdvancedCache_Model_Blocks extends Soon_AdvancedCache_Model_Abstract {

    
    /**
     * Prefix of model events names
     * 
     * @var string
     */
    protected $_eventPrefix = 'advancedcache_blocks';

    /**
     * Initialize resource model
     */
    protected function _construct() {
        $this->_init('advancedcache/blocks');
    }

    /**
     * Disable cache
     * 
     * @return: Soon_AdvancedCache_Model_Blocks
     */
    public function disable() {
        $this->setStatus(0)->save();
        return $this;
    }

    /**
     * Enable cache
     * 
     * @return: Soon_AdvancedCache_Model_Blocks
     */    
    public function enable() {
        $this->setStatus(1)->save();
        return $this;
    }

    /**
     * Clean cache
     * 
     * @return: Soon_AdvancedCache_Model_Blocks
     */    
    public function cleanAdminBlockCache() {
        $specialConfiguration = $this->getSpecialConfiguration();

        // If the block is special and needs extra cache
        if ($specialConfiguration != '') {

            switch ($specialConfiguration) {

                case Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_PRODUCTS_LIST:
                    $tag = Mage_Catalog_Model_Category::CACHE_TAG;
                    break;

                case Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_PRODUCT_VIEW:
                    $tag = Mage_Catalog_Model_Product::CACHE_TAG;
                    break;

                case Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_BREADCRUMBS:
                    $tag = 'breadcrumbs';
                    break;

                default :
                    $tag = $this->getIdentifier();
                    break;
            }
        }

        // For all other blocks which caching is standard
        else {
            $tag = $this->getIdentifier();
        }
        
        Mage::getSingleton('advancedcache/clean')->cleanBlockCache($tag);
        
        return $this;
    }

    /**
     * Processing object before save data
     *
     * @return Soon_AdvancedCache_Model_Blocks
     */
    protected function _beforeSave() {
        if (!$this->getBlockId()) {
            $this->isObjectNew(true);
        }

        Mage::dispatchEvent('model_save_before', array('object' => $this));
        Mage::dispatchEvent($this->_eventPrefix . '_save_before', $this->_getEventData());

        // Check if block class exists
        if (!class_exists($this->getBlockClass())) {
            $message = Mage::helper('advancedcache')->__('The block class you entered does not exist.');
            Mage::throwException($message);
        }

        // Check if block class is unique
        $existingBlockData = $this->getResource()->loadByBlockClass($this->getBlockClass());
        $existingBlock = new Varien_Object();
        $existingBlock->setData($existingBlockData);
        if ($existingBlock->getBlockClass() != '' && $existingBlock->getBlockClass() == $this->getBlockClass() && $existingBlock->getBlockId() != $this->getBlockId()) {
            $message = Mage::helper('advancedcache')->__('Block class must be unique.');
            Mage::throwException($message);
        }

        // Check if block identifier is unique
        $existingBlockData = $this->getResource()->loadByIdentifier($this->getIdentifier());
        $existingBlock = new Varien_Object();
        $existingBlock->setData($existingBlockData);
        if ($existingBlock->getIdentifier() != '' && $existingBlock->getIdentifier() == $this->getIdentifier() && $existingBlock->getBlockId() != $this->getBlockId()) {
            $message = Mage::helper('advancedcache')->__('Block identifier must be unique.');
            Mage::throwException($message);
        }
        return $this;
    }

}