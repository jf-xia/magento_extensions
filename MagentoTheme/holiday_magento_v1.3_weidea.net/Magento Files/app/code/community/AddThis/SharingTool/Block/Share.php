<?php

?>
<?php

class AddThis_SharingTool_Block_Share extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('addthis/sharingtool/share.phtml');
    }
    
    public function getPluginEnabledStatus(){
    	
    	return Mage::getStoreConfig('sharing_tool/general/enabled');
    }
    
    public function getPubId(){
    	 
    	return Mage::getStoreConfig('sharing_tool/general/pubid');
    }

    public function getButtonStyle(){
    
    	return Mage::getStoreConfig('sharing_tool/button_style/button_set');
    }
    
    public function getExcludeServices(){
    
    	return Mage::getStoreConfig('sharing_tool/api/services_exclude');
    }
    
    public function getCompactServices(){
    
    	return Mage::getStoreConfig('sharing_tool/api/services_compact');
    }
    
    public function getExpandedServices(){
    	 
    	return Mage::getStoreConfig('sharing_tool/api/services_expanded');
    }
    
    public function getCustomServiceName(){
    
    	return Mage::getStoreConfig('sharing_tool/custom_service/services_custom_name');
    }
    
    public function getCustomServiceUrl(){
    
    	return Mage::getStoreConfig('sharing_tool/custom_service/services_custom_url');
    }
    
    public function getCustomServiceIcon(){
    
    	return Mage::getStoreConfig('sharing_tool/custom_service/services_custom_icon');
    }
        
    public function getUiClick(){
    
    	return Mage::getStoreConfig('sharing_tool/api/ui_click');
    }
    
    public function getUiDelay(){
    
    	return Mage::getStoreConfig('sharing_tool/api/ui_delay');
    }
    
    public function getUiHover(){
    
    	return Mage::getStoreConfig('sharing_tool/api/ui_hover_direction');
    }
    
    public function getUiOpenWindows(){
    
    	return Mage::getStoreConfig('sharing_tool/api/ui_open_windows');
    }
    
    public function getUiLanguage(){
    
    	return Mage::getStoreConfig('sharing_tool/api/ui_language');
    }
    
    public function getUiOffsetTop(){
    
    	return Mage::getStoreConfig('sharing_tool/api/ui_offset_top');
    }
    
    public function getUiOffsetLeft(){
    
    	return Mage::getStoreConfig('sharing_tool/api/ui_offset_left');
    }    
  
    public function getUiCobrand(){
    
    	return Mage::getStoreConfig('sharing_tool/api/ui_cobrand');
    }
    
    public function getUiCss(){
    
    	return Mage::getStoreConfig('sharing_tool/api/ui_use_css');
    }
    
    public function getUiAddressBook(){
    
    	return Mage::getStoreConfig('sharing_tool/api/ui_use_addressbook');
    }
        
    public function getUiCompliant(){
    
    	return Mage::getStoreConfig('sharing_tool/api/ui_508_compliant');
    }
    
    public function getDataTrackClick(){
    
    	return Mage::getStoreConfig('sharing_tool/api/data_track_clickback');
    }

    public function getAddressBarShare(){
    
    	return Mage::getStoreConfig('sharing_tool/api/address_bar_share');
    }
    
    public function getDataGaTracker(){
    
    	return Mage::getStoreConfig('sharing_tool/api/data_ga_tracker');
    }
        
    public function getCustomUrl(){
    
    	return Mage::getStoreConfig('sharing_tool/custom_share/custom_url');
    }
    
    public function getCustomTitle(){
    
    	return Mage::getStoreConfig('sharing_tool/custom_share/custom_title');
    }
    
    public function getCustomDescription(){
    
    	return Mage::getStoreConfig('sharing_tool/custom_share/custom_description');
    }
    
}
