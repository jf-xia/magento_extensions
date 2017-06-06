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
class Soon_AdvancedCache_Block_Adminhtml_Expire extends Mage_Adminhtml_Block_Template {

    /**
     * Retrieve link to expiration save
     * 
     * @return string URL for saving cache config
     */
    public function getSaveExpirationUrl() {
        return $this->getUrl('*/advancedcache_expire/saveCacheExpire');
    }

    /**
     * Retrieve expiration lead time for given tag
     * 
     * @param string $tag
     * @return int
     */
    public function getExpire($tag) {
        $result = Mage::getModel('advancedcache/config')->getExpire($tag);
        return $result;
    }

}