<?php

class HM_DeveloperToolbar_IndexController extends Mage_Core_Controller_Front_Action
{		    
	public function hintsAction()
    {        
		$enabled = $this->getRequest()->getParam('enabled');
		$type = $this->getRequest()->getParam('type');
		
		$scope = $type === 'front' ? 'stores' : 'default';
		$scope_id = $type === 'front' ? Mage::app()->getStore()->getStoreId() : '0';
		Mage::getConfig()->saveConfig('dev/debug/template_hints', $enabled, $scope, $scope_id);
		Mage::getConfig()->saveConfig('dev/debug/template_hints_blocks', $enabled, $scope, $scope_id);
		
		$this->_redirectReferer();
    }
	
	public function logAction()
    {        
		$scope = 'stores';
		$scope_id = Mage::app()->getStore()->getStoreId();
		$enabled = $this->getRequest()->getParam('enabled');
		Mage::getConfig()->saveConfig('dev/log/active', $enabled, $scope, $scope_id);
		$this->_redirectReferer();
    }
	
	public function jsAction()
    {        
		$scope = 'stores';
		$scope_id = Mage::app()->getStore()->getStoreId();
		$enabled = $this->getRequest()->getParam('enabled');	
		Mage::getConfig()->saveConfig('dev/js/merge_files', $enabled, $scope, $scope_id);
		$this->_redirectReferer();
    }
	
	public function urlAction()
    {        
		$enabled = $this->getRequest()->getParam('enabled');
		Mage::getConfig()->saveConfig('web/url/use_store', $enabled);
		$this->_redirectReferer();
    }
	
	public function seoAction()
    {        
		$enabled = $this->getRequest()->getParam('enabled');	
		Mage::getConfig()->saveConfig('web/seo/use_rewrites', $enabled);
		$this->_redirectReferer();
    }
	
	public function translateAction()
    {        
		$scope = 'stores';
		$scope_id = Mage::app()->getStore()->getStoreId();
		$enabled = $this->getRequest()->getParam('enabled');	
		Mage::getConfig()->saveConfig('dev/translate_inline/active', $enabled, $scope, $scope_id);
		$this->_redirectReferer();
    }
	
	public function cacheAction()
    {        
		Mage::app()->cleanCache();
		$cacheTypes = array_keys(Mage::helper('core')->getCacheTypes());
		$enable = array();
        foreach ($cacheTypes as $type) {            
            $enable[$type] = 0;            		
        }
		Mage::app()->saveUseCache($enable);				
		$this->_redirectReferer();
    }
}
?>