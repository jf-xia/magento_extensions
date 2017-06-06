<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.0
 *
 */

	class EcommerceTeam_EasyCheckout_Block_Onepage_Abstract extends Mage_Checkout_Block_Onepage_Abstract{
		
		protected $helper;
		
		public function __construct(){
			$this->helper = Mage::helper('ecommerceteam_echeckout');;
		}
		
		public function getConfigData($node){
			return $this->helper->getConfigData($node);
		}
		
		public function getCountryHtmlSelect($type){
	        $countryId = $this->getAddress()->getCountryId();
	        if (is_null($countryId)) {
	            $countryId = Mage::getStoreConfig('general/country/default');
	        }
	        $select = $this->getLayout()->createBlock('core/html_select')
	            ->setName($type.'[country_id]')
	            ->setId($type.':country_id')
	            ->setTitle($this->__('Country'))
	            ->setClass('validate-select')
	            ->setValue($countryId)
	            ->setOptions($this->getCountryOptions());

	        return $select->getHtml();
	    }
		
	}