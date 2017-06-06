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
class Soon_AdvancedCache_Model_Abstract extends Mage_Core_Model_Abstract {

    /**
     * Config model
     * 
     * @var Soon_AdvancedCache_Model_Config
     */
    protected $_config;

    /**
     * Retrieve cache config model
     * 
     * @return Soon_AdvancedCache_Model_Config
     */
    public function getCacheConfig() {
        if (null == $this->_config) {
            $this->_config = Mage::helper('advancedcache')->getConfig();
        }

        return $this->_config;
    }

}