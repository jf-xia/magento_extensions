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
class Soon_AdvancedCache_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Retrieve cache config model
     * 
     * @return Soon_AdvancedCache_Model_Config
     */
    public function getConfig() {
        $config = Mage::getSingleton('advancedcache/config');
        return $config;
    }

}
