<?php
class Magestore_BannerSlider_Block_BannerSlider extends Mage_Core_Block_Template
{
	private $_display = '0';
	
	public function _prepareLayout()	{
		return parent::_prepareLayout();
	}
    
	public function getBannerSlider() { 
		if (!$this->hasData('bannerslider')) {
			$this->setData('bannerslider', Mage::registry('bannerslider'));
		}
		return $this->getData('bannerslider');			
	}
	
	public function setDisplay($display){
		$this->_display = $display;
	}
	
	public function getBannerCollection() {
		$collection = Mage::getModel('bannerslider/bannerslider')->getCollection()
			->addFieldToFilter('status',1)
			->setOrder('sorting_order','ASC');
			
		
		$current_store = Mage::app()->getStore()->getId();
		$banners = array();
		foreach ($collection as $banner) {
			$stores = explode(',',$banner->getStores());
			if (in_array(0,$stores) || in_array($current_store,$stores))
			//if ($banner->getStatus())
				$banners[] = $banner;
		}
		return $banners;
	}
	
	
	
	
}