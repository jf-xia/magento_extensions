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

/**
 * This is where one can add blocks to be added when the
 * Soon_AdvancedCache_Model_Add::addBlockCache() is called.
 * 
 * Adding blocks will be triggered for each block generation
 * event : core_block_abstract_to_html_before on frontend.
 * Please see config.xml
 * 
 */
class Soon_AdvancedCache_Model_Project_Add extends Soon_AdvancedCache_Model_Add {

    /**
     * Store id
     * 
     * @var int
     */
    protected $_storeId;

    /**
     * Retrieve store id
     * 
     * @return int
     */
    public function getStoreId() {
        if (null === $this->_storeId) {
            $this->_storeId = Mage::app()->getStore()->getId();
        }
        return $this->_storeId;
    }

    /**
     * Set cache parameters data
     * 
     * @return Soon_AdvancedCache_Model_Project_Add
     */
    public function getCacheParameters($block, $class) {

        /*
         * Additional code like:
         * 
          if ('Mage_Cms_Block_Page' == $class && $block->getPage()->getId()) {
          $lifetime = $this->getCacheConfig()->getExpire('cms');
          $key = 'cms_page_' . $block->getPage()->getId();
          $tags = array(Mage_Core_Model_Store::CACHE_TAG, Mage_Cms_Model_Page::CACHE_TAG . '_' . $block->getPage()->getId());
          }
         * 
         * 
         */
        
        if (isset($lifetime) && isset($key) && isset($tags)) {
            $this->setData(array('flag' => true, 'lifetime' => $lifetime, 'key' => $key, 'tags' => $tags));
        } else {
            $this->setFlag(false);
        }

        return $this;
    }

}