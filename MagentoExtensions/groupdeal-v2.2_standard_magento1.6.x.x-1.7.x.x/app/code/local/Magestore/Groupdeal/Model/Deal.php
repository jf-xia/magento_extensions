<?php

class Magestore_Groupdeal_Model_Deal extends Mage_Core_Model_Abstract{
	const WAITING 	= 6;
	const OPENING 	= 5;
	const REACHED 	= 4;
	const UNREACHED	= 3;
	const END 		= 2;
	const ENABLED 	= 1;
	const DISABLED	= 0;
	
    public function _construct()
    {
        parent::_construct();
        $this->_init('groupdeal/deal');
    }
	
	public function loadDealByProduct($productId){
		$deal = Mage::getModel('groupdeal/deal')->getCollection()
				->addFieldToFilter('product_id', $productId)
				->getFirstItem();
		return $deal;
	}
	
	
	public function getDealProduct(){
		return Mage::getModel('catalog/product')->load($this->getProductId());	
	}
	
	public function getProductIds(){
		$collection = Mage::getModel('groupdeal/productlist')->getCollection()
				->addFieldToFilter('deal_id', $this->getId());
		
		if(count($collection)) {
			foreach($collection as $item) {
				$productIds[] = $item->getProductId();
			}
		}
		return $productIds;
	}
	
	public function processUrlKey(){
		$urlKey = $this->getUrlKey(); 
		if(!$urlKey)
			$urlKey = Mage::helper('groupdeal/url')->formatUrlKey($this->getDealTitle()); 
	
		$isExist = Mage::helper('groupdeal/url')->isExistUrlKey($urlKey, $this->getId()); 
		if($isExist)
			$urlKey = $this->getId() . '-' . $urlKey;
		try{
			$this->setUrlKey($urlKey)->save();
		}catch(Exception $e){
		}
		return $urlKey;	
	}
	
	public function setStatus(){
		$nowTime = Mage::getModel('core/date')->gmtTimestamp();
		if(strtotime($this->getStartDatetime()) > $nowTime)
			$status = self::WAITING;
		elseif(strtotime($this->getStartDatetime()) <= $nowTime && strtotime($this->getEndDatetime()) > $nowTime){
			if($this->getBought() < $this->getMinimumPurchase())
				$status = self::OPENING;
			elseif(!$this->getMaximumPurchase() || ($this->getBought() < $this->getMaximumPurchase()))
				$status = self::REACHED;
			else
				$status = self::END;
		}elseif(strtotime($this->getEndTime()) <= $nowTime){
			if($this->getBought() < $this->getMinimumPurchase())
				$status = self::UNREACHED;
			else
				$status = self::END;
		}
		
		if($this->getDealStatus() != self::DISABLED){
			try{
				$beforeStatus = $this->getDealStatus(); 
				$this->setDealStatus($status)->save();
				//send mail to subscriber
				if($beforeStatus == self::WAITING && $this->getDealStatus() == self::OPENING){
					Mage::helper('groupdeal/email')->sendOpenDealEmailToSubscribers($this);
				}
				//deal end, disabled product
				if($this->getDealStatus() == self::END || $this->getDealStatus() == self::UNREACHED){
					$groupdealProduct = Mage::getModel('catalog/product')->load($this->getProductId());
					$groupdealProduct->setStatus(2)->save(); //disabled
				}
			}catch(Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		return $this;	
	}
	
	public function getDealUrl(){
		$dealId = $this->getDealId();
		
		if($this->getUrlKey())
			return Mage::getUrl('groupdeal').$this->getUrlKey();
		
		return Mage::getUrl('groupdeal/index/deal', array('id' => $dealId));
	}
	
	public function getValueHtml(){
		return Mage::helper('core')->currency(round($this->getDealValue(), 2));
	}
	
	public function getPriceHtml(){
		return Mage::helper('core')->currency(round($this->getDealPrice(), 2));
	}
	
	public function getYouSaveHtml(){
		return Mage::helper('core')->currency(round(($this->getDealValue() - $this->getDealPrice()), 2));
	}
	
	public function getFullImageUrl(){
		return Mage::getBaseUrl('media') . $this->getImageUrl();
	}
	
	public function getDiscountHtml(){
		return round(($this->getDealValue() - $this->getDealPrice())/$this->getDealValue()*100). Mage::helper('groupdeal')->__('%');
	}
	
	public function getFormatEndtime(){
		return Mage::getModel('core/date')->date('m/d/Y H:i', $this->getEndDatetime());
	}
	
	public function getNumberBoughtText(){
		if($this->getBought())
			return $this->getBought() . ' ' . Mage::helper('groupdeal')->__('bought');
		else
			return Mage::helper('groupdeal')->__('Be the first to buy!');
	}
	
	public function getSubscribers(){
		$productIds = $this->getProductIds();
		
		$categoryIds = array();
		foreach($productIds as $productId){
			$product = Mage::getModel('catalog/product')->load($productId);
			$categoryIds = array_merge($categoryIds, $product->getCategoryIds());
		}
		
		$dealCategoryIds =  array_unique($categoryIds);
		
		$subscribers = array();
		$collection = Mage::getModel('groupdeal/subscriber')->getCollection()
				->addFieldToFilter('status', 1);
		
		foreach($collection as $item){
			$subscriberCategoryIds = explode(',', $item->getCategories());
			if( $this->isAvaiablePrice($item->getPriceFrom(), $item->getPriceTo()) && $this->isAvaiableCategory($dealCategoryIds, $subscriberCategoryIds)){
				$subscriberName = $item->getFirstName() . ' ' . $item->getLastName();
				$subscriberEmail = $item->getEmail();
				$subscribers[] = array('name' => $subscriberName, 'email' => $subscriberEmail);
			}
		}
		return $subscribers;
	}
	
	protected function isAvaiablePrice($priceFrom, $priceTo){
		if($priceFrom == 0 && $priceTo == 0)
			return true;
		elseif($priceFrom <= $this->getDealPrice() && $priceTo >= $this->getDealPrice())
			return true;
		else
			return false;
	}
	
	protected function isAvaiableCategory($dealCategoryIds, $subscriberCategoryIds){
		$elTotal = count($dealCategoryIds) + count($subscriberCategoryIds);
		$mergeTotal = count(array_unique(array_merge($dealCategoryIds, $subscriberCategoryIds)));
		
		if( $elTotal > $mergeTotal)// has same element in 2 array 
			return true;
		else
			return false;
	}
	
	public function getThumbnailImage(){
		$product = $this->getDealProduct();
		return Mage::helper('catalog/image')->init($product, 'small_image')->resize(420, 255);
	}
	
	public function getStatusText(){
		$_helper = Mage::helper('groupdeal');
		if($this->getDealStatus() == self::REACHED)
        	return $_helper->__('The deal is on!');
		elseif($this->getDealStatus() == self::UNREACHED)
			return $_helper->__('The deal was uncompleted!');
		elseif($this->getDealStatus() == self::END)
			return $_helper->__('The deal was completed!');
	}
	
	public function getRemainQuantityText(){
		$_helper = Mage::helper('groupdeal');
		if($this->getDealStatus() == self::REACHED){
			if($this->getMaximumPurchase()){
				$remain = $this->getMaximumPurchase() - $this->getBought();
				return $_helper->__('Just %d more can get the deal', $remain);
             }else
				return $_helper->__('Unlimited quantity available');
		}elseif($this->getDealStatus() == self::OPENING){
			$remain = $this->getMinimumPurchase() - $this->getBought();
			return $_helper->__('%d more needed to get the deal', $remain);
		}
		return;
	}
	
	public function getDealDiscount(){
		return round(($this->getDealValue() - $this->getDealPrice())/$this->getDealValue()*100) . Mage::helper('groupdeal')->__('%');
	}
	
	public function getTimeText(){
		if($this->getDealStatus() == self::END || $this->getDealStatus() == self::UNREACHED){
			return Mage::helper('groupdeal')->__('Deal ended');
		}elseif($this->getDealStatus() == self::OPENING || $this->getDealStatus() == self::REACHED){
			return Mage::helper('groupdeal')->__('Time left to buy');
		}elseif($this->getDealStatus() == self::WAITING){
			return Mage::helper('groupdeal')->__('Time left to open');
		}
	}
	
	public function getRemainTime(){
		$nowGmtTime = Mage::getModel('core/date')->gmtTimestamp();
		 
		if($this->getDealStatus() == self::END || $this->getDealStatus() == self::UNREACHED){
			return $nowGmtTime - strtotime($this->getEndDatetime());
			
		}elseif($this->getDealStatus() == self::OPENING || $this->getDealStatus() == self::REACHED){
			return strtotime($this->getEndDatetime()) - $nowGmtTime;
			
		}elseif($this->getDealStatus() == self::WAITING){
			return strtotime($this->getStartDatetime()) - $nowGmtTime;
		}
	}
	
	public function getLeftTime(){
		$intTime = $this->getRemainTime();
		
		$days = floor($intTime/(24*3600));
		$hours = floor(($intTime%(24*3600))/3600);
		if($hours == 0)
			$hours = '00';
		elseif($hours < 10)
			$hours = '0' . (string)$hours;
			
		$minutes = floor((($intTime%(24*3600))%3600)/60);
		if($minutes == 0)
			$minute = '00';
		elseif($minutes < 10)
			$minutes = '0' . (string)$minutes;
		
		$seconds = floor((($intTime%(24*3600))%3600)%60);
		
		if($seconds == 0)
			$seconds = '00';
		elseif($seconds < 10)
			$seconds = '0' . (string)$seconds;
		
		if($days)
			return $days . 'd, ' . $hours . ':' . $minutes . ':' . $seconds ; 
		else
			return $hours . ':' . $minutes . ':' . $seconds;
	}
	
	public function getFormattedShortDescription()
	{
		 $maxchars = 240;
		 $short_description = substr($this->getShortDescription(), 0, $maxchars);
		 if(strlen($this->getShortDescription()) > $maxchars){
			 $pos = strrpos($short_description, " ");
			 if ($pos>0) {
				$short_description = substr($short_description, 0, $pos);
			 }
			return $short_description."...";
		 } else {
			return $this->getShortDescription();
		 }		
	}
}