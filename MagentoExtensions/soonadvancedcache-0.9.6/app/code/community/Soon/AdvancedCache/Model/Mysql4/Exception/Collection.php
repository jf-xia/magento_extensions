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
class Soon_AdvancedCache_Model_Mysql4_Exception_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    /**
     * Constructor
     *
     * Configures collection
     */
    protected function _construct() {
        parent::_construct();
        $this->_init('advancedcache/exception');
    }

}