<?php

class HM_DeveloperToolbar_IndexController extends Mage_Core_Controller_Front_Action
{		    
	function check()
	{
		$getConfigCustomer = Mage::getStoreConfig('dev/developertoolbar/enablename');
		$getCustomer = Mage::getSingleton('customer/customer')->load(Mage::getSingleton('customer/session')->getId())->getEmail();
		$getCustomerArray = explode(",",$getConfigCustomer);
		
		$getConfigIp = Mage::getStoreConfig('dev/developertoolbar/enableip');
		$getIp = $_SERVER["REMOTE_ADDR"];
		$getIprArray = explode(",",$getConfigIp);
		
		$getConfigIp==""?$checkip=false:$checkip=true;
		$enablename = false;
		for($i=0;$i<sizeof($getCustomerArray);$i++)
		{		
			if($getCustomer == trim($getCustomerArray[$i]))	
			{
				$enablename = true;
				break;		
			}		
		}
		
		$getConfigCustomer==""?$checkcustomer=false:$checkcustomer=true;
		
		$enableip = false;
		for($i=0;$i<sizeof($getIprArray);$i++)
		{		
			if($getIp == trim($getIprArray[$i]))	
			{
				$enableip = true;
				break;		
			}		
		}
		$case=false;
		if(!$checkip && !$checkcustomer && Mage::getStoreConfig('dev/developertoolbar/enabled')) $case=true;
		
		if($checkip && !$checkcustomer)
		{
			if(Mage::getStoreConfig('dev/developertoolbar/enabled') && $enableip) $case=true;
		}
		else if(!$checkip && $checkcustomer)
		{
			if(Mage::getStoreConfig('dev/developertoolbar/enabled') && $enablename) $case=true;
		}
		else if($checkip && $checkcustomer)
		{
			if(((Mage::getStoreConfig('dev/developertoolbar/enabled') && $enablename)) && ((Mage::getStoreConfig('dev/developertoolbar/enabled') && $enableip))) $case =true;
		}
		return $case;
	}

	public function hintsAction()
    {    
		if(!$this->check())
		$this->_forward('noRoute');
		else{			
	    	$enabled = $this->getRequest()->getParam('enabled');
			$type = $this->getRequest()->getParam('type');
			$scope = $type === 'front' ? 'stores' : 'default';
			$scope_id = $type === 'front' ? Mage::app()->getStore()->getStoreId() : '0';
			Mage::getConfig()->saveConfig('dev/debug/template_hints', $enabled, $scope, $scope_id);
			Mage::getConfig()->saveConfig('dev/debug/template_hints_blocks', $enabled, $scope, $scope_id);
			
			$this->_redirectReferer();
		}
    }
	
	public function logAction()
    {   
    	if(!$this->check())
		$this->_forward('noRoute');
		else{  
			$scope = 'stores';
			$scope_id = Mage::app()->getStore()->getStoreId();
			$enabled = $this->getRequest()->getParam('enabled');
			Mage::getConfig()->saveConfig('dev/log/active', $enabled, $scope, $scope_id); 
			$this->_redirectReferer();
		}
    }
	
	public function jsAction()
    {       

		if(!$this->check())
		$this->_forward('noRoute');
		else{ 
			$scope = 'stores';
			$scope_id = Mage::app()->getStore()->getStoreId();
			$enabled = $this->getRequest()->getParam('enabled');	
			Mage::getConfig()->saveConfig('dev/js/merge_files', $enabled, $scope, $scope_id);
	    	if(!Mage::getStoreConfig('dev/js/merge_files') || !Mage::getStoreConfig('dev/developertoolbar/enabled'))
			$this->_forward('noRoute'); 		
			$this->_redirectReferer();
		}
    }
	
	public function urlAction()
    {      
		if(!$this->check())
		$this->_forward('noRoute');
		else{ 
			$enabled = $this->getRequest()->getParam('enabled');
			Mage::getConfig()->saveConfig('web/url/use_store', $enabled);
	    	if(!Mage::getStoreConfig('web/url/use_store') || !Mage::getStoreConfig('dev/developertoolbar/enabled'))
			$this->_forward('noRoute');  		
			$this->_redirectReferer();
		}
    }
	
	public function seoAction()
    {        
		if(!$this->check())
		$this->_forward('noRoute');
		else{ 
			$enabled = $this->getRequest()->getParam('enabled');	
			Mage::getConfig()->saveConfig('web/seo/use_rewrites', $enabled);
	    	if(!Mage::getStoreConfig('web/seo/use_rewrites') || !Mage::getStoreConfig('dev/developertoolbar/enabled'))
			$this->_forward('noRoute');		
			$this->_redirectReferer();
		}
    }
	
	public function translateAction()
    {     
		if(!$this->check())
		$this->_forward('noRoute');
		else{ 
			$scope = 'stores';
			$scope_id = Mage::app()->getStore()->getStoreId();
			$enabled = $this->getRequest()->getParam('enabled');	
			Mage::getConfig()->saveConfig('dev/translate_inline/active', $enabled, $scope, $scope_id);
	    	if(!Mage::getStoreConfig('dev/translate_inline/active') || !Mage::getStoreConfig('dev/developertoolbar/enabled'))
			$this->_forward('noRoute'); 		
			$this->_redirectReferer();
		}
    }
	
	public function cacheAction()
    {       
		if(!$this->check())
		$this->_forward('noRoute');
		else{ 
			Mage::app()->cleanCache();
			$cacheTypes = array_keys(Mage::helper('core')->getCacheTypes());
			$enable = array();
	        foreach ($cacheTypes as $type) {            
	            $enable[$type] = 0;            		
	        }
			Mage::app()->saveUseCache($enable);		
	    	if(!Mage::getStoreConfig('dev/developertoolbar/enabled'))
			$this->_forward('noRoute');				
			$this->_redirectReferer();
		}
    }
}
?>