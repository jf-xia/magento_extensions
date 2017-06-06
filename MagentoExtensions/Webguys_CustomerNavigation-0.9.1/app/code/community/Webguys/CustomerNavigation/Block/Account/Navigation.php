<?php 

class Webguys_CustomerNavigation_Block_Account_Navigation extends Mage_Customer_Block_Account_Navigation {

	public function getLinks()
    {
    	$pre_links 	  = $this->_links;
    	$tmp_links    = array(); 
		$this->_links = array();
		
		foreach ($pre_links as $_link) {
			if( Mage::getStoreConfig( 'customernavigation/settings/show_' . $_link->getName() ) ) {
				$tmp_links[ Mage::getStoreConfig( 'customernavigation/reorder/position_' . $_link->getName() ) ] = $_link;				
			}            	
		}
		
		ksort( $tmp_links );
		
		foreach ($tmp_links as $key=>$_link) {
			if( Mage::getStoreConfig( 'customernavigation/settings/show_' . $_link->getName() ) ) {
				$this->addLink($_link->getName(), $_link->getPath(), $_link->getLabel());				
			}            	
		}
		return $this->_links;
    }
}