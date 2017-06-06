<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.0
 *
 */

class EcommerceTeam_EasyCheckout_Model_System_Conf_Source_Cmsblock{
	
	protected $_options;
	
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = Mage::getResourceModel('cms/block_collection')
                ->load()
                ->toOptionArray();
            array_unshift($this->_options, array('value'=>'', 'label'=>Mage::helper('ecommerceteam_echeckout')->__('Please select a static block ...')));
        }
        return $this->_options;
    }
}