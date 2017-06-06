<?php
class Mdlb_Mlayer_Block_Mlayer extends Mage_Core_Block_Template
{
	private $_display = '0';
	
	public function _prepareLayout()	{
		return parent::_prepareLayout();
	}
    
	public function getMlayer() { 
		if (!$this->hasData('mlayer')) {
			$this->setData('mlayer', Mage::registry('mlayer'));
		}
		return $this->getData('mlayer');			
	}
	
	public function setDisplay($display){
		$this->_display = $display;
	}
	
	public function getBannerCollection() {
		$collection = Mage::getModel('mlayer/mlayer')->getCollection()
			->addFieldToFilter('status',1)
			->addFieldToFilter('is_home',$this->_display);
		
		
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
	
	public function getDelayTime() {
		$delay = (int) Mage::getStoreConfig('mlayer/settings/time_delay');
		$delay = $delay * 1000;
		return $delay;
	}
	
	public function isShowDescription(){
		return (int)Mage::getStoreConfig('mlayer/settings/show_description');
	}
	
	public function getImageWidth() {
		return (int)Mage::getStoreConfig('mlayer/settings/image_width');
	}
	
	public function getImageHeight() {
		return (int)Mage::getStoreConfig('mlayer/settings/image_height');
	}
	public function getMetroBanner() {
		return (int)Mage::getStoreConfig('mlayer/settings/metro_banner');
	}
	public function getFlexBanner() {
		return (int)Mage::getStoreConfig('mlayer/flex_banner_settings/flex_banner');
	}
	public function getListStyle(){
		return (int)Mage::getStoreConfig('mlayer/flex_banner_settings/list_style');
	}
	public function getListAnimaiton(){
		return (int)Mage::getStoreConfig('mlayer/flex_banner_settings/animation_style');
	}
	public function getAnimationSpeed(){
		return (int)Mage::getStoreConfig('mlayer/flex_banner_settings/animation_speed');
	}
	public function getSlideshowSpeed(){
		return (int)Mage::getStoreConfig('mlayer/flex_banner_settings/slideshow_speed');
	}
	public function getSequencebBanner(){
		return (int)Mage::getStoreConfig('mlayer/sequence_banner_settings/sequence_banner');
	}
	public function getSequencebDis(){
		return (int)Mage::getStoreConfig('mlayer/sequence_banner_settings/show_description_sequence');
	}
	public function getAnimationSpeedSequence(){
		return (int)Mage::getStoreConfig('mlayer/sequence_banner_settings/animation_speed_sequence');
	}
	public function getSlideshowSpeedSequence(){
		return (int)Mage::getStoreConfig('mlayer/sequence_banner_settings/slideshow_speed_sequence');
	}
	public function getButtontextsequence(){
		return Mage::getStoreConfig('mlayer/sequence_banner_settings/buttontext_sequence');
	}
	public function getNivoBanner(){
		return (int)Mage::getStoreConfig('mlayer/nivo_banner_settings/nivo_banner');
	}
	public function getNivoDis(){
		return (int)Mage::getStoreConfig('mlayer/nivo_banner_settings/show_description_nivo');
	}
	public function getNivoEffect(){
		return (int)Mage::getStoreConfig('mlayer/nivo_banner_settings/nivo_effect');
	}
	public function getAnimSpeed(){
		return (int)Mage::getStoreConfig('mlayer/nivo_banner_settings/anim_speed');
	}
	public function getPauseTime(){
		return (int)Mage::getStoreConfig('mlayer/nivo_banner_settings/pause_time');
	}
	public function getBannertype(){
		return (int)Mage::getStoreConfig('mlayer/mainsettings/select_banner');
	}
}