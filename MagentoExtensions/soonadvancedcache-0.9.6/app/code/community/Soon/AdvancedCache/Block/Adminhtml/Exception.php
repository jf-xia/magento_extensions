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
class Soon_AdvancedCache_Block_Adminhtml_Exception extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        parent::__construct();
        $this->_controller = 'adminhtml_exception';
        $this->_blockGroup = 'advancedcache';
        $this->_headerText = Mage::helper('advancedcache')->__('Exceptions Manager');
    }

}